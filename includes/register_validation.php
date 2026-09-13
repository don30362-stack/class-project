<?php

const REGISTER_UPLOADS_SESSION_KEY = 'registration_uploads';

function requestString(array $source, string $key): ?string
{
    return isset($source[$key]) && is_string($source[$key]) ? trim($source[$key]) : null;
}

function stringLength(string $value): ?int
{
    $result = preg_match_all('/./us', $value, $matches);
    return $result === false ? null : $result;
}

function validTaiwanId(string $value): bool
{
    $value = strtoupper($value);
    if (preg_match('/\A[A-Z][12][0-9]{8}\z/D', $value) !== 1) return false;
    $letters = array('A'=>10,'B'=>11,'C'=>12,'D'=>13,'E'=>14,'F'=>15,'G'=>16,'H'=>17,'I'=>34,'J'=>18,'K'=>19,'L'=>20,'M'=>21,'N'=>22,'O'=>35,'P'=>23,'Q'=>24,'R'=>25,'S'=>26,'T'=>27,'U'=>28,'V'=>29,'W'=>32,'X'=>30,'Y'=>31,'Z'=>33);
    $code = $letters[$value[0]];
    $sum = intdiv($code, 10) + ($code % 10) * 9;
    for ($index = 1; $index <= 8; $index++) $sum += ((int)$value[$index]) * (9 - $index);
    $sum += (int)$value[9];
    return $sum % 10 === 0;
}

function validateRegistration(PDO $link, array $post): array
{
    $email = requestString($post, 'email'); $cname = requestString($post, 'cname');
    $tssn = requestString($post, 'tssn'); $birthday = requestString($post, 'birthday');
    $mobile = requestString($post, 'mobile'); $city = requestString($post, 'myCity');
    $town = requestString($post, 'myTown'); $zip = requestString($post, 'myZip');
    $address = requestString($post, 'address'); $upload = requestString($post, 'uploadname');
    if ($email === null || $email === '' || strlen($email) > 100 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) return array(false, 'Email 格式不正確。');
    $duplicate = $link->prepare('SELECT 1 FROM member WHERE email = :email LIMIT 1'); $duplicate->execute(array(':email' => $email));
    if ($duplicate->fetchColumn()) return array(false, '此 Email 已註冊。');
    $nameLength = $cname === null ? null : stringLength($cname);
    if ($nameLength === null || $nameLength < 1 || $nameLength > 30) return array(false, '姓名必須為 1～30 個字元。');
    if ($tssn === null || !validTaiwanId($tssn)) return array(false, '身分證字號格式不正確。');
    if ($mobile === null || preg_match('/\A09[0-9]{8}\z/D', $mobile) !== 1) return array(false, '手機號碼格式不正確。');
    if ($birthday === null || preg_match('/\A\d{4}-\d{2}-\d{2}\z/D', $birthday) !== 1) return array(false, '出生日期格式不正確。');
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $birthday); $dateErrors = DateTimeImmutable::getLastErrors();
    if (!$date || ($dateErrors !== false && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0)) || $date->format('Y-m-d') !== $birthday || $date > new DateTimeImmutable('today')) return array(false, '出生日期不正確。');
    $addressLength = $address === null ? null : stringLength($address);
    if ($addressLength === null || $addressLength < 1 || $addressLength > 200) return array(false, '地址必須為 1～200 個字元。');
    if ($city === null || $town === null || $zip === null || preg_match('/\A[1-9][0-9]*\z/D', $city) !== 1 || preg_match('/\A[1-9][0-9]*\z/D', $town) !== 1) return array(false, '縣市、地區或郵遞區號不正確。');
    $location = $link->prepare('SELECT 1 FROM town AS t INNER JOIN city AS c ON c.AutoNo = t.AutoNo WHERE c.AutoNo = :city AND t.townNo = :town AND t.Post = :zip AND c.State = 0 AND t.State = 0 LIMIT 1');
    $location->execute(array(':city'=>(int)$city, ':town'=>(int)$town, ':zip'=>$zip));
    if (!$location->fetchColumn()) return array(false, '縣市、地區與郵遞區號不相符。');
    $imgname = 'avatar.svg';
    if ($upload !== null && $upload !== '') {
        $uploads = $_SESSION[REGISTER_UPLOADS_SESSION_KEY] ?? array();
        if (!is_array($uploads) || !isset($uploads[$upload]) || $uploads[$upload] !== true) return array(false, '上傳圖片驗證失敗，請重新上傳。');
        $imgname = $upload;
    }
    return array(true, array('email'=>$email,'cname'=>$cname,'tssn'=>strtoupper($tssn),'birthday'=>$birthday,'mobile'=>$mobile,'zip'=>$zip,'address'=>$address,'imgname'=>$imgname));
}
