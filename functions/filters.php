<?php

//add_filter('user_has_cap', 'mtax_filter_user_has_cap');
//
//add_filter( 'get_term', function ($_term, $taxonomy) {
//    dump($_term);
//} );

function mtax_filter_user_has_cap($q)
{
    dump(11111);
    dump($q);
}
