<?php

namespace App\Model;

use App\Entity\Site;

class SearchOuting
{
    public ?string $name = null;

    public ?Site $site = null;

    public ?\DateTimeInterface $startDate = null;

    public ?\DateTimeInterface $endDate = null;

    public ?bool $isOrganizer = false;

    public ?bool $isRegistered = false;

    public ?bool $isNotRegistered = false;

    public ?bool $isFinished = false;

}