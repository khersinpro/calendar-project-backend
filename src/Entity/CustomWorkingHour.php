<?php

namespace App\Entity;

use App\Repository\CustomWorkingHourRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomWorkingHourRepository::class)]
class CustomWorkingHour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $open_time = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $close_time = null;

    #[ORM\ManyToOne(inversedBy: 'custom_working_hours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CustomScheduleDay $custom_schedule_day = null;

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

    public function getCustomScheduleDay(): ?CustomScheduleDay
    {
        return $this->custom_schedule_day;
    }

    public function setCustomScheduleDay(?CustomScheduleDay $custom_schedule_day): static
    {
        $this->custom_schedule_day = $custom_schedule_day;

        return $this;
    }
}
