<?php

function mscra_get_rss_feed($attributes) {
    if (is_admin()) {
        return;
    }
    if (!isset($attributes['url'])) {
        return;
    }    

    $url = $attributes['url'];
    $top = (isset($attributes['count'])) ? $attributes['count'] : 12;
    $call_action = (isset($attributes['callaction'])) ? $attributes['callaction'] : __('read more', 'mscra-automation');

    $dom0 = new DOMDocument();
    $dom0->load($url);
    $titles = $dom0->getElementsByTagName("title");
    $date = $dom0->getElementsByTagName("pubDate");
    $link = $dom0->getElementsByTagName("link");
    $descrip = $dom0->getElementsByTagName("description");
    $n = $titles->length;
    if ($top < $n) {
        $n = $top;
    }
    $strecho = '';
    for ($i = 1; $i < $n; $i++) {
        if ($i > $top) {
            break;
        }
        if ($titles->item($i)) {

            /* try {
              $times = date("Y-m-d",(strtotime($date->item($i)->textContent)));
              } catch (Exception $e) {
              $times ='';
              } */

            $Titular = str_replace('?', "'", utf8_encode(utf8_decode($titles->item($i)->textContent)));
            $cos = str_replace('?', "'", utf8_encode(utf8_decode($descrip->item($i)->textContent)));
            $url = $link->item($i)->textContent;


            /* if (fmod($i,2) == 0 ){
              $strecho .= '<div class="team-member slideinleft">';
              }else{
              $strecho .= '<div class="team-member slideinright">';
              } */
            $strecho .= '<div>';
            $strecho .= '<h4>' . $Titular . '</h4>';
            $strecho .= '<p>' . $cos . '</p>';
            $strecho .= '<i><a href="' . $url . '" title="" target=_blank> '.$call_action.'</a></i>';

            $strecho .= '</div><br>';
        }
    }
    unset($dom0, $titles, $date, $link, $descrip);
    return $strecho;
}

add_shortcode('mscra_rss_feed', 'mscra_get_rss_feed');

