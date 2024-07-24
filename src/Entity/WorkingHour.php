<?php

namespace App\Entity;

use App\Repository\WorkingHourRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: WorkingHourRepository::class)]
class WorkingHour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['schedule.read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['schedule.read'])]
    private ?\DateTimeInterface $open_time = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['schedule.read'])]
    private ?\DateTimeInterface $close_time = null;

    #[ORM\ManyToOne(inversedBy: 'working_hours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ScheduleDay $schedule_day = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpenTime(): ?\DateTimeInterface
    {
        return $this->open_time;
    }

    public function setOpenTime(\DateTimeInterface $open_time): static
    {
        $this->open_time = $open_time;

        return $this;
    }

    public function getCloseTime(): ?\DateTimeInterface
    {
        return $this->close_time;
    }

    public function setCloseTime(\DateTimeInterface $close_time): static
    {
        $this->close_time = $close_time;

        return $this;
    }

    public function getScheduleDay(): ?ScheduleDay
    {
        return $this->schedule_day;
    }

    public function setScheduleDay(?ScheduleDay $schedule_day): static
    {
        $this->schedule_day = $schedule_day;

        return $this;
    }
}
