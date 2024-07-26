<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Enum\WorkingDayStatusEnum;
use App\Repository\CustomScheduleDayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomScheduleDayRepository::class)]
class CustomScheduleDay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\Date(message: 'The date must be a valid date')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(enumType: WorkingDayStatusEnum::class)]
    #[Assert\NotBlank]
    #[Assert\Choice(callback: [WorkingDayStatusEnum::class, 'values'])]
    private ?WorkingDayStatusEnum $status = null;

    #[ORM\ManyToOne(inversedBy: 'custom_schedule_days')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Schedule $schedule = null;

    /**
     * @var Collection<int, CustomWorkingHour>
     */
    #[ORM\OneToMany(targetEntity: CustomWorkingHour::class, mappedBy: 'custom_schedule_day', orphanRemoval: true)]
    private Collection $custom_working_hours;

    public function __construct()
    {
        $this->custom_working_hours = new ArrayCollection();
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

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(?Schedule $schedule): static
    {
        $this->schedule = $schedule;

        return $this;
    }

        /**
     * @return Collection<int, WorkingHour>
     */
    public function getCustomWorkingHours(): Collection
    {
        return $this->custom_working_hours;
    }

    public function addCustomWorkingHour(CustomWorkingHour $customWorkingHour): static
    {
        if (!$this->custom_working_hours->contains($customWorkingHour)) {
            $this->custom_working_hours->add($customWorkingHour);
            $customWorkingHour->setCustomScheduleDay($this);
        }

        return $this;
    }

    public function removeCustomWorkingHour(CustomWorkingHour $customWorkingHour): static
    {
        if ($this->custom_working_hours->removeElement($customWorkingHour)) {
            // set the owning side to null (unless already changed)
            if ($customWorkingHour->getCustomScheduleDay() === $this) {
                $customWorkingHour->setCustomScheduleDay(null);
            }
        }

        return $this;
    }
}
