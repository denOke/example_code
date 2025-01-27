<?php

namespace App\Es\Contracts\Repository;

interface DictionaryRepositoryContract
{
    public function getBranchList($type = 'individual');
    public function getBranchByCode($code, $type = 'individual');
    public function getBannersSectionsList();
    public function getGroupsList();
    public function getGroupByCode($code);
    public function getUserActions();
    public function getEventsList();
    public function getCriteriaList();
    public function getUserTypes();
    public function getReceiptDeliveryTypes();
    public function getTicketCategoriesList();
    public function getTicketSubcategoriesList();
    public function getPaymentMethods();
    public function getMeterDefaultRooms();
    public function getNotificationChannels();
    public function getNotificationPeriods();
    public function getSyncCriteriaCodes();
    public function getSocial();
    public function getEntityLoginText();
    public function getAll();
}
