<?php

global $MyRadio;
if (!isset($MyRadio)) {
    include_once '../inc/defines.php';
    include_once '../inc/my_radio.php';
    include_once '../inc/utils.php';
    $my_key = $_GET['key'];
    $MyRadio = new my_radio($my_key, WP_MSCRA_LANG_DEF, 0);

    if ($MyRadio->RESPOSTA_MESSAGE != 'OK') {
        if ($MyRadio->IS_DEGUG == true) {
            $title = 'MSC API Error';
            $subtitle = $MyRadio->RESPOSTA_MESSAGE;
        }
    }
}

$img_width = '100';
$dir_images = $_GET['img_dir'] . '/';
$url_images = $_GET['img_url'] . '/';
$url_base_share = $_GET['share_url'];
$url_base_download = $_GET['url_download'];
$url_podcast = $_GET['url_podcast'];
$def_img = $_GET['di'];

$Vars[0] = 'id=' . $_GET['id'];
$list = $MyRadio->QueryGetTable(seccions::PODCAST, sub_seccions::LISTEN, $Vars, false);

if ($MyRadio->RESPOSTA_ROWS > 0) {
    $counter = 0;
    $id = ($list['item']['ID']);
    $type = ($list['item']['TYPE']);
    $title = utf8_encode(($list['item']['NAME']));
    $subtitle = utf8_encode(($list['item']['DESCRIP']));
    $time_end = ($list['item']['TIME_END']);
    $link = ($list['item']['LINK']);
    $time_remain = 15000;

    $id = ($list['item']['ID']);
    $type = ($list['item']['TYPE']);
    $hexid = bin2hex($id . ',' . $type);
    $URL_Share = $url_base_share . '?ref=' . $hexid;

    switch ($type) {
        case TIP_AUTOMATIC_LLISTA:
            $img_mame = 'disc_img-' . $id . '.jpg';
            if (strlen($list['item']['LINK']) > 0) {
                $URL_Download = $url_base_jamendo . ($list['item']['LINK']);
            } else { $URL_Download = '';}
            break;
        case TIP_AUTOMATIC_RADIOFORMULA:
            $img_mame = 'disc_img-' . $id . '.jpg';
            if (strlen($list['item']['LINK']) > 0) {
                $URL_Download = $url_base_jamendo . ($list['item']['LINK']);
            } else { $URL_Download = '';}
            break;
        case TIP_AUTOMATIC_PROGRAMA:
            $img_mame = 'prg_img-' . $id . '.jpg';
            //todo: download prg
            $urlmp3 = $url_podcast . '/' . ($list['item']['LINK']);
            $URL_Download = $url_base_download . $urlmp3 . '&filename=' . urlencode($title);
            break;
        case TIP_DIRECTE_:
            $img_mame = 'prg_img-' . $id . '.jpg';
            $URL_Download = '';
            break;
        case TIP_CONEX_CENTRAL_:
            $img_mame = 'radio_img.jpg';
            $URL_Download = '';
            break;
    }
    $PathToSaveImg = $dir_images . $img_mame;
    $PathToShowImg = $url_images . $img_mame;
    if (!file_exists($PathToSaveImg)) {
        if (mscra_getImage(base64_decode($list['item']['IMAGE']), $PathToSaveImg, $img_width) == true) {
            //canvia a imatge per defecte
            $PathToShowImg = $def_img;
        }
    }
    $doc = new DOMDocument("1.0", "ISO-8859-15");
    $root = $doc->createElement("msc-soft");
    $node = $doc->createElement("header");

    $st = $doc->createElement("title");
    $st->appendChild($doc->createTextNode($title));
    $node->appendChild($st);

    $cd = $doc->createElement("subtitle");
    $cd->appendChild($doc->createTextNode($subtitle));
    $node->appendChild($cd);

    $rows = $doc->createElement("image");
    $rows->appendChild($doc->createTextNode($PathToShowImg));
    $node->appendChild($rows);

    $rows = $doc->createElement("remain");
    $rows->appendChild($doc->createTextNode($time_remain));
    $node->appendChild($rows);

    $rows = $doc->createElement("url_share");
    $rows->appendChild($doc->createTextNode($URL_Share));
    $node->appendChild($rows);

    $rows = $doc->createElement("url_download");
    $rows->appendChild($doc->createTextNode($URL_Download));
    $node->appendChild($rows);

    $root->appendChild($node);
    $doc->appendChild($root);

    header("content-type: application/xml; charset=ISO-8859-15");
    $doc->formatOutput = true;
    print $doc->saveXML();
}
