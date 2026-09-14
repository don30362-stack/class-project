<?php

require_once __DIR__ . '/registration_upload.php';

function requestString(array $source, string $key): ?string
{
    return isset($source[$key]) && is_string($source[$key]) ? trim($source[$key]) : null;
}

function stringLength(string $value): ?int
{
    $result = preg_match_all('/./us', $value, $matches);
    return $result === false ? null : $result;
}

function validateRegistration(PDO $link, array $post): array
{
    $email = requestString($post, 'email'); $cname = requestString($post, 'cname');
    $birthday = requestString($post, 'birthday');
    $mobile = requestString($post, 'mobile'); $city = requestString($post, 'myCity');
    $town = requestString($post, 'myTown'); $zip = requestString($post, 'myZip');
    $address = requestString($post, 'address'); $upload = requestString($post, 'uploadname');
    if ($email === null || $email === '' || strlen($email) > 100 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) return array(false, 'Email 格式不正確。');
    $duplicate = $link->prepare('SELECT 1 FROM member WHERE email = :email LIMIT 1'); $duplicate->execute(array(':email' => $email));
    if ($duplicate->fetchColumn()) return array(false, '此 Email 已註冊。');
    $nameLength = $cname === null ? null : stringLength($cname);
    if ($nameLength === null || $nameLength < 1 || $nameLength > 30) return array(false, '姓名必須為 1～30 個字元。');
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
        if (!isRegistrationAvatarFilename($upload) || registrationUploadFilename() !== $upload) return array(false, '上傳圖片驗證失敗，請重新上傳。');
        $imgname = $upload;
    }
    return array(true, array('email'=>$email,'cname'=>$cname,'birthday'=>$birthday,'mobile'=>$mobile,'zip'=>$zip,'city_id'=>(int)$city,'town_id'=>(int)$town,'address'=>$address,'imgname'=>$imgname));
}
