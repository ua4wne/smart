<?php

namespace app\modules\main\models;

use Yii;
use app\models\LibraryModel;
use yii\base\Model;
use app\models\SMSRU;
use app\modules\main\models\Config;
use stdClass;

class Sms extends Model
{
    private $api_id;
    public $phone;
    public $message;
    public $from_mail = false;

    public function rules()
    {
        return [
            [['phone', 'message'], 'required'],
            [['phone'], 'string', 'max' => 12],
            [['message'], 'string', 'max' => 70],
            [['from_mail'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone' => 'Телефон получателя',
            'message' => 'Сообщение',
            'from_mail' => 'Отправлять через почту @sms.ru'
        ];
    }

    //отправка одного смс
    public function SendSms(){
        //определяем api_id
        $this->api_id = Config::findOne(['param'=>'API_KEY_SMSRU'])->val;
        if(empty($this->api_id)){
            $msg = "Не найдено значение константы API_KEY_SMSRU в настройках системы! Отправка смс не возможна.";
            LibraryModel::AddEventLog('error',$msg);
            return 'ERR';
        }
        $smsru = new SMSRU($this->api_id); // Ваш уникальный программный ключ

        $data = new stdClass();
        $data->to = $this->phone; //'79685507780';
        $data->text = $this->message; //'Hello World'; // Текст сообщения
    // $data->from = ''; // Если у вас уже одобрен буквенный отправитель, его можно указать здесь, в противном случае будет использоваться ваш отправитель по умолчанию
    // $data->time = time() + 7*60*60; // Отложить отправку на 7 часов
    // $data->translit = 1; // Перевести все русские символы в латиницу (позволяет сэкономить на длине СМС)
    // $data->test = 1; // Позволяет выполнить запрос в тестовом режиме без реальной отправки сообщения
    // $data->partner_id = '1'; // Можно указать ваш ID партнера, если вы интегрируете код в чужую систему
        $sms = $smsru->send_one($data); // Отправка сообщения и возврат данных в переменную

        if ($sms->status == "OK") { // Запрос выполнен успешно
            Yii::$app->session->setFlash('success', 'Сообщение успешно отправлено! ID сообщения: ' . $sms->sms_id);
            //$msg = 'Сообщение успешно отправлено! ID сообщения:' . $sms->sms_id;
            //LibraryModel::AddEventLog('info',$msg);
        } else {
            Yii::$app->session->setFlash('error', 'В процессе отправки сообщения возникла ошибка! Код ошибки: ' . $sms->status_code .' Текст ошибки: '.$sms->status_text);
            //$msg = 'В процессе отправки сообщения возникла ошибка! Код ошибки: <strong>' . $sms->status_code .'</strong>. Текст ошибки: '.$sms->status_text;
            //LibraryModel::AddEventLog('error',$msg);
        }
        return $sms->status;
    }

    public function GetCost(){
        //определяем api_id
        $this->api_id = Config::findOne(['param'=>'API_KEY_SMSRU'])->val;
        if(empty($this->api_id)){
            $msg = "Не найдено значение константы API_KEY_SMSRU в настройках системы! Отправка смс не возможна.";
            LibraryModel::AddEventLog('error',$msg);
            return 'ERR';
        }
        $smsru = new SMSRU($this->api_id); // Ваш уникальный программный ключ
        $data = new stdClass();
        $data->to = $this->phone; //'79685507780';
        $data->text = $this->message; //'Hello World'; // Текст сообщения

        //фиксируем стоимость отправленных смс
        $request = $smsru->getCost($data); // Отправка сообщений и возврат данных в переменную
        if ($request->status == "OK") { // Запрос выполнен успешно
            foreach ($request->sms as $phone => $data) { // Перебираем массив СМС сообщений
                if ($data->status == "OK") { // Сообщение обработано
                    //echo "Номер: $phone ";
                    $cost = $data->cost;
                    //echo "Длина в СМС: $data->sms ";
                }
            }
            //$msg = "Общая стоимость: $request->total_cost руб.";
            //$msg.= " Общая длина СМС: $request->total_sms ";
            //LibraryModel::AddEventLog('info',$msg);
        } else {
            $msg = "Ошибка при выполнении запроса стоимости. ";
            $msg.= "Код ошибки: <strong>$request->status_code</strong>. ";
            $msg.= " Текст ошибки: $request->status_text. ";
            LibraryModel::AddEventLog('error',$msg);
            $request->status;
        }
        return $cost;
    }

    public function SendViaMail(){
        return Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->user->identity->email)
            ->setTo(Yii::$app->params['mail_sms'])
            ->setSubject($this->phone)
            ->setHtmlBody($this->message)
            ->send();
    }

    //узнать баланс
    public function GetBalanse(){
        //определяем api_id
        $this->api_id = Config::findOne(['param'=>'API_KEY_SMSRU'])->val;
        if(empty($this->api_id)){
            $msg = "Не найдено значение константы API_KEY_SMSRU в настройках системы! Отправка смс не возможна.";
            LibraryModel::AddEventLog('error',$msg);
            return 'ERR';
        }
        $smsru = new SMSRU($this->api_id); // Ваш уникальный программный ключ

        $request = $smsru->getBalance();

        if ($request->status == "OK") { // Запрос выполнен успешно
            Yii::$app->session->setFlash('success', 'Ваш баланс:' . $request->balance);
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при выполнении запроса.! Код ошибки: ' . $request->status_code .'<br>Текст ошибки: '.$request->status_text);
            return 'ERR';
        }
        return 'OK';
    }

    //узнать лимит
    public function GetLimit(){
        //определяем api_id
        $this->api_id = Config::findOne(['param'=>'API_KEY_SMSRU'])->val;
        if(empty($this->api_id)){
            $msg = "Не найдено значение константы API_KEY_SMSRU в настройках системы! Отправка смс не возможна.";
            LibraryModel::AddEventLog('error',$msg);
            return 'ERR';
        }
        $smsru = new SMSRU($this->api_id); // Ваш уникальный программный ключ
        $request = $smsru->getLimit();

        if ($request->status == "OK") { // Запрос выполнен успешно
            Yii::$app->session->setFlash('success', 'Ваш лимит: ' . $request->balance . 'Использовано сегодня: ' . $request->used_today);
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при выполнении запроса.! Код ошибки: ' . $request->status_code .'<br>Текст ошибки: '.$request->status_text);
            return 'ERR';
        }
    }

    private function StatusCode($id){
        $codes = ['100'=>'Команда выполнена успешно', '101'=>'Сообщение передается оператору', '102'=>'Сообщение отправлено (в пути)', '103'=>'Сообщение доставлено',
            '104'=>'Сообщение не может быть доставлено: время жизни истекло', '105'=>'Сообщение не может быть доставлено: удалено оператором', '106'=>'Сообщение не может быть доставлено: сбой в телефоне',
            '107'=>'Сообщение не может быть доставлено: неизвестная причина', '108'=>'Сообщение не может быть доставлено: отклонено', '130'=>'Сообщение не может быть доставлено: превышено количество сообщений на этот номер в день',
            '131'=>'Сообщение не может быть доставлено: превышено количество одинаковых сообщений на этот номер в минуту', '132'=>'Сообщение не может быть доставлено: превышено количество одинаковых сообщений на этот номер в день',
            '200'=>'Неправильный api_id', '201'=>'Не хватает средств на лицевом счету', '202'=>'Неправильно указан получатель', '203'=>'Нет текста сообщения', '204'=>'Имя отправителя не согласовано с администрацией',
            '205'=>'Сообщение слишком длинное (превышает 8 СМС)', '206'=>'Будет превышен или уже превышен дневной лимит на отправку сообщений', '207'=>'На этот номер (или один из номеров) нельзя отправлять сообщения, либо указано более 100 номеров в списке получателей',
            '208'=>'Параметр time указан неправильно', '209'=>'Вы добавили этот номер (или один из номеров) в стоп-лист', '210'=>'Используется GET, где необходимо использовать POST', '211'=>'Метод не найден',
            '212'=>'Текст сообщения необходимо передать в кодировке UTF-8 (вы передали в другой кодировке)', '220'=>'Сервис временно недоступен, попробуйте чуть позже.', '230'=>'Превышен общий лимит количества сообщений на этот номер в день.',
            '231'=>'Превышен лимит одинаковых сообщений на этот номер в минуту.', '232'=>'Превышен лимит одинаковых сообщений на этот номер в день.', '300'=>'Неправильный token (возможно истек срок действия, либо ваш IP изменился)',
            '301'=>'Неправильный пароль, либо пользователь не найден', '302'=>'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)'
            ];
        foreach ($codes as $key=>$val){
            if($key==$id)
                return $val;
        }
        return 'Код не найден!';
    }

}