<?php

namespace App\Entity;

use App\Repository\WorkingHourRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WorkingHourRepository::class)]
class WorkingHour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['schedule.read', 'working_hour.read', 'schedule_day.read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['schedule.read', 'working_hour.read', 'schedule_day.read', 'working_hour.create', 'working_hour.update'])]
    #[Assert\NotBlank]
    #[Assert\Type('\DateTimeInterface')]
    private ?\DateTimeInterface $open_time = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['schedule.read', 'working_hour.read', 'schedule_day.read', 'working_hour.create', 'working_hour.update'])]
    #[Assert\NotBlank]
    #[Assert\Type('\DateTimeInterface')]
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
