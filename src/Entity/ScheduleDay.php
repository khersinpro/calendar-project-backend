<?php

namespace App\Entity;

use App\Enum\DayEnum;
use App\Enum\WorkingDayStatusEnum;
use App\Repository\ScheduleDayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleDayRepository::class)]
class ScheduleDay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: DayEnum::class)]
    private ?DayEnum $day_of_week = null;

    #[ORM\Column(enumType: WorkingDayStatusEnum::class)]
    private ?WorkingDayStatusEnum $status = null;

    #[ORM\ManyToOne(inversedBy: 'schedule_days')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Schedule $schedule = null;

    /**
     * @var Collection<int, WorkingHour>
     */
    #[ORM\OneToMany(targetEntity: WorkingHour::class, mappedBy: 'schedule_day', orphanRemoval: true)]
    private Collection $working_hours;

    public function __construct()
    {
        $this->working_hours = new ArrayCollection();
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
    public function getWorkingHours(): Collection
    {
        return $this->working_hours;
    }

    public function addWorkingHour(WorkingHour $working_hour): static
    {
        if (!$this->working_hours->contains($working_hour)) {
            $this->working_hours->add($working_hour);
            $working_hour->setScheduleDay($this);
        }

        return $this;
    }

    public function removeWorkingHour(WorkingHour $working_hour): static
    {
        if ($this->working_hours->removeElement($working_hour)) {
            // set the owning side to null (unless already changed)
            if ($working_hour->getScheduleDay() === $this) {
                $working_hour->setScheduleDay(null);
            }
        }

        return $this;
    }
}
