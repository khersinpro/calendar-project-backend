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

    #[ORM\ManyToOne(inversedBy: 'customWorkingHours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CustomPlanningDay $custom_planning_day = null;

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

    public function getCustomPlanningDay(): ?CustomPlanningDay
    {
        return $this->custom_planning_day;
    }

    public function setCustomPlanningDay(?CustomPlanningDay $custom_planning_day): static
    {
        $this->custom_planning_day = $custom_planning_day;

        return $this;
    }
}
