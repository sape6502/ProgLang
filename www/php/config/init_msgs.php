<?php
    //TODO: Switch login and register page to shared err_ vars
    $_SESSION['err_fdims'] = false;
    $_SESSION['nametaken'] = false;
    $_SESSION['passmatch'] = false;
    $_SESSION['connError'] = false;
    $_SESSION['fieldsSet'] = false;
    $_SESSION['incorrect'] = false;
    $_SESSION['err_dbconn'] = false;
    $_SESSION['err_ftoobig'] = false;
    $_SESSION['err_fupfail'] = false;
    $_SESSION['err_fields_ch'] = false;
    $_SESSION['err_fields_dl'] = false;
    $_SESSION['err_fields_im'] = false;
    $_SESSION['err_passmatch'] = false;
    $_SESSION['succ_passchange'] = false;
    $_SESSION['err_passwrong_ch'] = false;
    $_SESSION['err_passwrong_dl'] = false;
    $_SESSION['err_passwrong_im'] = false;
