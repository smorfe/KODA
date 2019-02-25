<?php
/*
 |----------------------------------------------------------------
 |  Background Helpers
 |----------------------------------------------------------------
 */
function koda_bg($attachmentId = null, $imgSize = '') {
    global $itemId, $imgId, $itemArr;

    $itemId = uniqid('bg-');
    $imgId = $attachmentId;
    $itemArr[] = array('item-id' => $itemId, 'img-id' => $imgId, 'img-size' => $imgSize);
    echo 'data-bg='.$itemId;
}

add_action('wp_footer', 'koda_bg_styles', 50);

function koda_bg_styles() {
    global $itemId, $imgId, $itemArr, $imgSize;

    $styles = [];
    foreach($itemArr as $item) {
        $styles[] = sprintf(' %s { background-image: url(%s);}',
            '[data-bg="'.$item['item-id'].'"]', wp_get_attachment_image_url($item['img-id'], $item['img-size']));
    }

    $style = sprintf("<style type=\"text/css\">\n%s\n</style>", implode('', $styles));

    echo $style;
}