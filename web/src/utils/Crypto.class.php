<?php

/**
 * Created by PhpStorm.
 * User: eygle
 * Date: 8/18/16
 * Time: 4:06 PM
 */
class Crypto {
    const KEY_FOLDER = "/home/eygle/";

    public static function encryptInfo($plainText, $keyName) {
        $key = uniqid() . sha1(md5(mt_rand( 0, 0xffff ) . uniqid() . md5(mt_rand( 0, 0xffff ))));
        $cypher = self::AESEncrypt($plainText, $key);
        file_put_contents(self::KEY_FOLDER . "$keyName.key", $key);
        return $cypher;
    }

    public static function decryptInfo($cypher, $keyName) {
        $key = file_get_contents(self::KEY_FOLDER . "$keyName.key");
        return self::AESDecrypt($cypher, $key);
    }

    private static function AESEncrypt($val, $ky) {
        $key="\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
        for($a=0;$a<strlen($ky);$a++)
            $key[$a%16]=chr(ord($key[$a%16]) ^ ord($ky[$a]));
        $mode=MCRYPT_MODE_ECB;
        $enc=MCRYPT_RIJNDAEL_128;
        $val=str_pad($val, (16*(floor(strlen($val) / 16)+(strlen($val) % 16==0?2:1))), chr(16-(strlen($val) % 16)));
        return mcrypt_encrypt($enc, $key, $val, $mode, mcrypt_create_iv( mcrypt_get_iv_size($enc, $mode), MCRYPT_DEV_URANDOM));
    }

    private static function AESDecrypt($val, $ky)
    {
        $key="\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
        for($a=0;$a<strlen($ky);$a++)
            $key[$a%16]=chr(ord($key[$a%16]) ^ ord($ky[$a]));
        $mode = MCRYPT_MODE_ECB;
        $enc = MCRYPT_RIJNDAEL_128;
        $dec = @mcrypt_decrypt($enc, $key, $val, $mode, @mcrypt_create_iv( @mcrypt_get_iv_size($enc, $mode), MCRYPT_DEV_URANDOM ) );
        return rtrim($dec,(( ord(substr($dec,strlen($dec)-1,1))>=0 and ord(substr($dec, strlen($dec)-1,1))<=16)? chr(ord( substr($dec,strlen($dec)-1,1))):null));
    }
}