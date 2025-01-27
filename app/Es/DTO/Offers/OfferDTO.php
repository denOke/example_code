<?php

namespace App\Es\DTO\Offers;

use App\Es\Models\Offer;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class OfferDTO implements Arrayable
{
    private string $id;
    private string $partnerName;
    private string $mainText;
    private string $additionalText;
    private string $secondAdditionalText;
    private Collection $category;
    private Collection $subcategory;
    private Collection $siteSections;
    private string $url;
    private string $clientType;
    private string $channel;
    private bool $active;
    private string $startTime;
    private string $endTime;
    private int $sort;
    private string $target;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): OfferDTO
    {
        $this->id = $id;
        return $this;
    }

    public function getPartnerName(): string
    {
        return $this->partnerName;
    }

    public function setPartnerName($partnerName): OfferDTO
    {
        $this->partnerName = $partnerName;
        return $this;
    }

    public function getMainText(): ?string
    {
        return $this->mainText;
    }

    public function setMainText($mainText): OfferDTO
    {
        $this->mainText = $mainText;
        return $this;
    }

    public function getAdditionalText(): ?string
    {
        return $this->additionalText;
    }

    public function setAdditionalText($additionalText): OfferDTO
    {
        $this->additionalText = $additionalText;
        return $this;
    }

    public function getSecondAdditionalText(): ?string
    {
        return $this->secondAdditionalText;
    }

    public function setSecondAdditionalText($secondAdditionalText): OfferDTO
    {
        $this->secondAdditionalText = $secondAdditionalText;
        return $this;
    }

    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function setCategory(Collection $category): OfferDTO
    {
        $this->category = $category;
        return $this;
    }

    public function getSubcategory(): Collection
    {
        return $this->subcategory;
    }

    public function setSubcategory(Collection $subcategory): OfferDTO
    {
        $this->subcategory = $subcategory;
        return $this;
    }

    public function getSiteSections(): Collection
    {
        return $this->siteSections;
    }

    public function setSiteSections(Collection $siteSections): OfferDTO
    {
        $this->siteSections = $siteSections;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl($url): OfferDTO
    {
        $this->url = $url;
        return $this;
    }

    public function getClientType(): string
    {
        return $this->clientType;
    }

    public function setClientType($clientType): OfferDTO
    {
        $this->clientType = $clientType;
        return $this;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function setChannel($channel): OfferDTO
    {
        $this->channel = $channel;
        return $this;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): OfferDTO
    {
        $this->active = $active;
        return $this;
    }

    public function getStartTime(): string
    {
        return $this->startTime;
    }

    public function setStartTime($startTime): OfferDTO
    {
        $this->startTime = $startTime;
        return $this;
    }

    public function getEndTime(): string
    {
        return $this->endTime;
    }

    public function setEndTime($endTime): OfferDTO
    {
        $this->endTime = $endTime;
        return $this;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort($sort): OfferDTO
    {
        $this->sort = $sort;
        return $this;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function setTarget($target): OfferDTO
    {
        $this->target = $target;
        return $this;
    }

    public static function createByModel(Offer $offer): self
    {
        return (new self())
            ->setId($offer['id'])
            ->setPartnerName($offer['name'])
            ->setMainText($offer['main_text'])
            ->setAdditionalText($offer['additional_text'])
            ->setSecondAdditionalText($offer['second_additional_text'])
            ->setStartTime($offer['start_date'])
            ->setEndTime($offer['end_date'])
            ->setUrl($offer['link'] ?: '')
            ->setChannel($offer['channel'])
            ->setActive($offer['active'] ?: false)
            ->setClientType($offer['user_type'])
            ->setSort($offer['sort'])
            ->setTarget($offer['target'])
            ->setCategory($offer['categories'])
            ->setSubcategory($offer['subcategories'])
            ->setSiteSections($offer['sections']);
    }

    public function toArray(): array
    {
        return [
            'partnerName' => $this->getPartnerName(),
            'mainText' => $this->getMainText(),
            'additionalText' => $this->getAdditionalText(),
            'secondAdditionalText' => $this->getSecondAdditionalText(),
            'category' => $this->getCategory(),
            'subcategory' => $this->getSubcategory(),
            'publication' => [
                'activity' => $this->getActive(),
                'startTime' => $this->getStartTime(),
                'endTime' => $this->getEndTime(),
            ],
            'siteSections' => $this->getSiteSections(),
            'url' => $this->getUrl(),
            'target' => $this->getTarget(),
            'clientType' => $this->getClientType(),
            'channel' => $this->getChannel(),
            'sort' => $this->getSort(),
        ];
    }
}
