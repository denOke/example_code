<?php

namespace App\Es\Repositories;

use App\Es\Cache\DictionaryCacheKeychain;
use App\Es\Contracts\Repository\DictionaryRepositoryContract;
use App\Models\BannerCategory;
use App\Models\Branch;
use App\Models\Criteria;
use App\Models\Event;
use App\Models\Group;
use App\Models\MeterDefaultRoom;
use App\Models\TicketCategory;
use App\Models\TicketSubcategory;
use Illuminate\Support\Facades\Cache;

class DictionaryRepository implements DictionaryRepositoryContract
{
    use DictionaryCacheKeychain;

    const PAYMENT_METHOD_APPLE_PAY = 'apple-pay';
    const PAYMENT_METHOD_CREDIT_CARD = 'credit-card';
    const PAYMENT_METHOD_GOOGLE_PAY = 'google-pay';
    const PAYMENT_METHOD_QR_SBP = 'qr-sbp';

    private const ADDITIONAL_BRANCHES = [ //todo убрать после окончания тестирования ЮЛ
        'chuvashia',
        'saratov',
        'kirov',
        'oren',
        'udm',
        'samara',
        'ekb',
        'novgorod',
        'mordovia',
        'perm',
        'komi',
        'kmes',
        'vladimir',
    ];

    private const IP_FOR_ADDITIONAL_BRANCHES = [ //todo убрать после окончания тестирования ЮЛ
        '185.116.202.192',
        '195.43.34.36',
        '195.43.34.37',
        '95.167.234.141',
        '95.167.232.5',
        '10.5.130.150',
        '10.59.0.128',
        '10.81.17.156',
        '10.59.0.205',
        '10.169.17.62',
        '10.163.8.39',
        '10.169.19.84',
        '10.205.1.86',
        '10.217.13.250',
        '10.43.0.96',
        '10.81.120.81',
        '10.5.130.119',
        '10.81.120.62',
        '10.81.120.28',
        '10.81.17.140',
        '10.52.119.23',
        '10.64.17.95',
        '10.208.129.62',
        '10.8.58.27',
        '10.23.0.221',
        '10.25.0.166',
        '10.25.48.41',
        '10.25.48.42',
        '213.135.113.114',
        //
        '10.162.24.72',
        '10.163.8.32',
        '10.217.5.61',
        '10.23.0.222',
        '10.32.241.208',
        '10.38.33.141',
        '10.38.33.39',
        '10.38.33.62',
        '10.52.9.22',
        '10.59.0.128',
        '10.59.0.249',
        '10.64.16.112',
        '10.64.17.49',
        '10.64.18.120',
        '10.64.18.128',
        '10.64.18.36',
        '10.64.21.174',
        '10.64.21.183',
        '10.65.24.13',
        '10.65.24.69',
        '10.65.27.205',
        '10.65.40.161',
        '10.65.40.180',
        '10.65.40.197',
        '10.65.40.212',
        '10.65.40.36',
        '10.65.40.68',
        '10.65.43.207',
        '10.65.43.209',
        '10.65.73.94',
        '10.72.132.59',
        '10.72.134.158',
        '10.8.58.27',
        '10.81.120.28',
        '10.81.120.62',
        '10.81.120.81',
        '10.81.17.140',
        '10.81.17.156',
        '195.43.34.36',
        '195.43.34.37',
        '95.167.232.5',
        '95.167.234.141',
        '10.81.118.197',
        '10.205.1.92',
        '10.217.5.35',
        '10.81.151.151',
        '10.81.152.44',
        '10.81.153.61',
        '10.25.48.67',
        '10.25.48.66',
        '109.252.35.167',
        '10.205.80.49',
        '10.205.8.22',
        '10.205.1.32',
        '10.205.1.167',
        '10.169.19.178',
        '10.52.8.119',
        '10.52.9.2',
        '10.52.9.106',
        '10.52.8.248'
    ];

    private const USER_ACTIONS = [
        'registration' => 'Регистрация',
        'registration_old' => 'Переход из старого личного кабинета',
        'authentication' => 'Вход',
        'authentication_old' => 'Вход из старого личного кабинета',
        'logout' => 'Выход',
        'delete' => 'Удаление пользователя',
        'change_password' => 'Изменение пароля',
        'reset_password' => 'Восстановление пароля',
        'change_main_contact' => 'Изменение авторизационного контакта',
        'change_distribution_contact' => 'Изменение контакта для рассылок',
        'account_connect' => 'Добавление нового лицевого счёта',
        'account_disconnect' => 'Удаление лицевого счёта',
        'send_compose_report' => 'Запрос на формирование и отправку документа',
        'account_receipt' => 'Изменение способа доставки квитанции',
        'added_contact' => 'Добавление нового контакта',
        'deleted_contact' => 'Удаление контакта',
        'change_email_contact' => 'Изменение контактного Email',
        'create_ticket' => 'Создание обращения',
        'account_identification' => 'Идентификация договора ЮЛ',
        'complain_ticket' => 'Жалоба на долгий ответ на обращение',
        'complain_ticket_answer' => 'Жалоба на ответ в обращении',
        'ordered_call_back' => 'Заказ обратного звонка',
        'send_meter_data' => 'Передача показаний',
        'download_receipt' => 'Загрузка квитанции',
        'download_report' => 'Загрузка отчёта',
        'made_payment' => 'Оплата счёта',
        'send_push' => 'Отправлено Push уведомление',
        'enabled_auto_payment' => 'Подключение автоплатёжа',
        'disabled_auto_payment' => 'Отключение автоплатёжа',
        'subscribed_email' => 'Подписка на рассылку по email',
        'subscribed_sms' => 'Подписка на рассылку по sms',
        'unsubscribed_email' => 'Отписка от рассылки по email',
        'unsubscribed_sms' => 'Отписка от рассылки по sms',
        'send_meter_template_file' => 'Загрузил шаблон передачи показаний файлом',
    ];

    const BRANCHES_CODES_BY_TYPE = [
        'individual' => [
            'chuvashia',
            'saratov',
            'kirov',
            'oren',
            'udm',
            'samara',
//            'novgorod',
            'ekb',
            'ivanovo',
            'ulyanov',
            'vladimir',
        ],
        'entity' => [
            'chuvashia',
            'saratov',
            'kirov',
            'oren',
            'udm',
            'samara',
            'ekb',
            'ivanovo',
            'novgorod',
            'penza',
            'ulyanov',
            'mordovia',
            'perm',
            'komi',
//            'kmes',
            'vladimir',
        ],
        'vendor'=>[
            'chuvashia',
            'saratov',
            'kirov',
            'oren',
            'udm',
            'samara',
            'ekb',
            'ivanovo',
        ]
    ];

    public function getBranchList($type = 'individual')
    {
//        return Cache::rememberForever(
//            $this->getBranchListKey($type, in_array($_SERVER['REMOTE_ADDR'], self::IP_FOR_ADDITIONAL_BRANCHES)), //todo убрать после окончания тестирования ЮЛ
//            function () use ($type) {
                if (is_null($type)) {
                    return Branch::orderBy('name')->get();
                } else {
                    $all = self::BRANCHES_CODES_BY_TYPE[$type];

                    //добавляем дополнительные филиалы, если IP в списке тестировщиков
                    /*if ($type == 'entity') {//todo убрать после окончания тестирования ЮЛ
                        $all = array_merge(
                            in_array($_SERVER['REMOTE_ADDR'], self::IP_FOR_ADDITIONAL_BRANCHES) ? self::ADDITIONAL_BRANCHES : [],
                            $all
                        );
                    }*/
                    //добавил для ФЛ
//                    if ($type == 'individual') {
//                        $all = array_merge(
//                            in_array($_SERVER['REMOTE_ADDR'], self::IP_FOR_ADDITIONAL_BRANCHES) ? self::ADDITIONAL_BRANCHES : [],
//                            $all
//                        );
//                    }

                    return Branch::whereIn('code', $all)->orderBy('name')->get();
                }
//            }
//        );
    }

    public function getAll()
    {
        return Branch::all();
    }

    public function getBranchByCode($code, $type = 'individual')
    {
        return $this->getBranchList($type)->firstWhere('code', $code);
    }

    public function getBannersSectionsList()
    {
        return Cache::rememberForever(
            $this->getBannersSectionsListKey(),
            function () {
                return BannerCategory::all();
            }
        );
    }

    public function getGroupsList()
    {
        return Cache::rememberForever(
            $this->getGroupsListKey(),
            function () {
                return Group::orderBy('name')->get();
            }
        );
    }

    public function getGroupByCode($code)
    {
        return $this->getGroupsList()->firstWhere('code', $code);
    }

    public function getUserActions()
    {
        return self::USER_ACTIONS;
    }

    public function getEventsList()
    {
        return Cache::rememberForever(
            $this->getEventsListKey(),
            function () {
                return Event::all();
            }
        );
    }

    public function getCriteriaList()
    {
        return Cache::rememberForever(
            $this->getCriteriaListKey(),
            function () {
                return Criteria::all();
            }
        );
    }

    public function getUserTypes()
    {
        return collect([
            [
                'name' => 'Физическое лицо',
                'code' => 'individual'
            ],
            [
                'name' => 'Юридическое лицо',
                'code' => 'entity'
            ],
        ]);
    }

    public function getReceiptDeliveryTypes()
    {
        return collect([
            [
                'name' => 'На электронную почту',
                'code' => 'email',
            ],
            [
                'name' => 'Почтой на адрес',
                'code' => 'address',
            ],
        ]);
    }

    public function getTicketCategoriesList(string $userType = 'individual', $tss = 0)
    {
        $arrType = ['all', $userType];
        if ($tss > 0) $arrType[] =  $userType . '_tss';
        if ($tss > 1) $arrType[] =  $userType . '_tss_gvs';
        return Cache::rememberForever(
            $this->getTicketCategoriesListKey($arrType),
            function () use ($arrType) {
                return TicketCategory::query()->whereIn('user_type', $arrType)->get();
            }
        );
    }

    public function getTicketSubcategoriesList(string $userType = 'individual', $tss = 0)
    {
        $arrType = ['all', $userType];
        if ($tss > 0) $arrType[] =  $userType . '_tss';
        if ($tss > 1) $arrType[] =  $userType . '_tss_gvs';
        return Cache::rememberForever(
            $this->getTicketSubcategoriesListKey($arrType),
            function () use ($arrType) {
                return TicketSubcategory::with('category')->whereIn('user_type', $arrType)->get();
            }
        );
    }

    public function getPaymentMethods()
    {
        return [
            self::PAYMENT_METHOD_APPLE_PAY,
            self::PAYMENT_METHOD_CREDIT_CARD,
            self::PAYMENT_METHOD_GOOGLE_PAY,
            self::PAYMENT_METHOD_QR_SBP,
        ];
    }

    public function getMeterDefaultRooms()
    {
        return Cache::rememberForever(
            $this->getMeterDefaultRoomsListKey(),
            function () {
                return MeterDefaultRoom::all();
            }
        );
    }

    public function getNotificationChannels()
    {
        return collect([
            [
                'name' => 'SMS',
                'code' => 'sms',
                'fields' => [
                    'text',
                ],
            ],
            [
                'name' => 'E-mail',
                'code' => 'email',
                'fields' => [
                    'title',
                    'text',
                ],
            ],
            [
                'name' => 'Push',
                'code' => 'push',
                'fields' => [
                    'title',
                    'text',
                ],
            ],
            [
                'name' => 'Push и SMS',
                'code' => 'push_sms',
                'fields' => [
                    'title',
                    'text',
                ],
            ],
            [
                'name' => 'Лента уведомлений',
                'code' => 'notification_list',
                'fields' => [
                    'title',
                    'text',
                ],
            ],
            [
                'name' => 'Модальное окно',
                'code' => 'popup',
                'fields' => [
                    'title',
                    'text',
                    'button_text',
                    'link',
                    'faq',
                ],
            ],
        ]);
    }

    public function getNotificationPeriods()
    {
        return collect([
            'daily',
            'weekly',
            'monthly'
        ]);
    }

    public function getSyncCriteriaCodes()
    {
        return collect([
            'client_type',
            'birthdate',
            'balance',
            'branch_code',
            'debt_period',
            'receipt_delivery_type',
            'accrual_date',
            'verified_date',
            'meter_data_send',
            'meter_data_send_date',
        ]);
    }

    public function getSocial()
    {
        return collect([
            'facebook',
            'vkontakte',
            'odnoklassniki',
            'esia',
            'apple',
        ]);
    }

    public function getEntityLoginText()
    {
        return collect([
            [
                'code' => 'web',
                'text' => 'Доступны договоры по Ульяновскому, Ивановскому, Оренбургскому, Владимирскому, Удмуртскому, Марий Эл и Чувашия, Нижегородскому, Кировскому, Самарскому, Саратовскому, Мордовия, Пермскому, Свердловскому и Пензенскому филиалам, Республике Коми.',
            ],
            [
                'code' => 'mobile',
                'text' => 'Доступно для клиентов следующих регионов: Ульяновская область, Ивановская область, Оренбургская область, Владимирская область, Удмуртская Республика, Республика Марий Эл, Республика Чувашия, Пензенская область, Нижегородская область, Кировская область, Самарская область, Республика Мордовия, Пермский край, Саратовская область, Свердловская область, Республика Коми.',
            ],
        ]);
    }
}
