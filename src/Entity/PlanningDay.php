<?php

namespace App\Entity;

use App\Enum\DayEnum;
use App\Enum\WorkingDayStatusEnum;
use App\Repository\PlanningDayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanningDayRepository::class)]
class PlanningDay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: DayEnum::class)]
    private ?DayEnum $day_of_week = null;

    #[ORM\Column(enumType: WorkingDayStatusEnum::class)]
    private ?WorkingDayStatusEnum $status = null;

    #[ORM\ManyToOne(inversedBy: 'planningDays')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Planning $planning = null;

    /**
     * @var Collection<int, WorkingHour>
     */
    #[ORM\OneToMany(targetEntity: WorkingHour::class, mappedBy: 'planning_day', orphanRemoval: true)]
    private Collection $workingHours;

    public function __construct()
    {
        $this->workingHours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayOfWeek(): ?DayEnum
    {
        return $this->day_of_week;
    }

    public function setDayOfWeek(DayEnum $day_of_week): static
    {
        $this->day_of_week = $day_of_week;

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
    public function getWorkingHours(): Collection
    {
        return $this->workingHours;
    }

    public function addWorkingHour(WorkingHour $workingHour): static
    {
        if (!$this->workingHours->contains($workingHour)) {
            $this->workingHours->add($workingHour);
            $workingHour->setPlanningDay($this);
        }

        return $this;
    }

    public function removeWorkingHour(WorkingHour $workingHour): static
    {
        if ($this->workingHours->removeElement($workingHour)) {
            // set the owning side to null (unless already changed)
            if ($workingHour->getPlanningDay() === $this) {
                $workingHour->setPlanningDay(null);
            }
        }

        return $this;
    }
}
