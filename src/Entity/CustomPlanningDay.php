<?php

namespace App\Entity;

use App\Enum\WorkingDayStatusEnum;
use App\Repository\CustomPlanningDayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomPlanningDayRepository::class)]
class CustomPlanningDay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(enumType: WorkingDayStatusEnum::class)]
    private ?WorkingDayStatusEnum $status = null;

    #[ORM\ManyToOne(inversedBy: 'customPlanningDays')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Planning $planning = null;

    /**
     * @var Collection<int, CustomWorkingHour>
     */
    #[ORM\OneToMany(targetEntity: CustomWorkingHour::class, mappedBy: 'custom_planning_day', orphanRemoval: true)]
    private Collection $customWorkingHours;

    public function __construct()
    {
        $this->customWorkingHours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): ?WorkingDayStatusEnum
    {
        return $this->status;
    }

    public function setStatus(WorkingDayStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPlanning(): ?Planning
    {
        return $this->planning;
    }

    public function setPlanning(?Planning $planning): static
    {
        $this->planning = $planning;

        return $this;
    }

        /**
     * @return Collection<int, WorkingHour>
     */
    public function getCustomWorkingHours(): Collection
    {
        return $this->customWorkingHours;
    }

    public function addCustomWorkingHour(CustomWorkingHour $customWorkingHour): static
    {
        if (!$this->customWorkingHours->contains($customWorkingHour)) {
            $this->customWorkingHours->add($customWorkingHour);
            $customWorkingHour->setCustomPlanningDay($this);
        }

        return $this;
    }

    public function removeCustomWorkingHour(CustomWorkingHour $customWorkingHour): static
    {
        if ($this->customWorkingHours->removeElement($customWorkingHour)) {
            // set the owning side to null (unless already changed)
            if ($customWorkingHour->getCustomPlanningDay() === $this) {
                $customWorkingHour->setCustomPlanningDay(null);
            }
        }

        return $this;
    }
}
