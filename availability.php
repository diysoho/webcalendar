<?php
/* $Id$
 * Page Description:
 * Display a timebar view of a single day.
 *
 * Input Parameters:
 * month (*) - specify the starting month of the timebar
 * day (*) - specify the starting day of the timebar
 * year (*) - specify the starting year of the timebar
 * users (*) - csv of users to include
 * (*) required field
 *
 * Security:
 * Must have "allow view others" enabled ($ALLOW_VIEW_OTHER) in
 *   System Settings unless the user is an admin user ($is_admin).
 */

include_once 'includes/init.php';
// Don't allow users to use this feature if "allow view others" is disabled.
if ( $ALLOW_VIEW_OTHER == 'N' && ! $is_admin )
  // not allowed...
  exit;

// input args in URL
// users: list of comma-separated users
$programStr = translate ( 'Program Error' ) . ': ';
if ( empty ( $users ) ) {
  echo $programStr . str_replace ( 'XXX', translate ( 'user' ),
    translate ( 'No XXX specified!' ) );
  exit;
} elseif ( empty ( $year ) ) {
  echo $programStr . str_replace ( 'XXX', translate ( 'year' ),
    $translations['No XXX specified!'] );
  exit;
} elseif ( empty ( $month ) ) {
  echo $programStr . str_replace ( 'XXX', translate ( 'month' ),
    $translations['No XXX specified!'] );
  exit;
} elseif ( empty ( $day ) ) {
  echo $programStr . str_replace ( 'XXX', translate ( 'day' ),
    $translations['No XXX specified!'] );
  exit;
}

print_header (
  array ( 'js/availability.php/false/' . "$month/$day/$year/"
   . getGetValue ( 'form' ) ), '', 'onload="focus ();"', true, false, true );

$next_url = $prev_url = '?users=' . $users;
$date = date ( 'Ymd', $time );
$time = mktime ( 0, 0, 0, $month, $day, $year );
$next_url .= strftime ( '&amp;year=%Y&amp;month=%m&amp;day=%d', $time + 86400 );
$prev_url .= strftime ( '&amp;year=%Y&amp;month=%m&amp;day=%d', $time - 86400 );
$span = ( $WORK_DAY_END_HOUR - $WORK_DAY_START_HOUR ) * 3 + 1;

$users = explode ( ',', $users );

echo '
    <div style="width:99%;">
      <a title="' . $translations['Previous'] . '" class="prev" href="'
 . $prev_url . '"><img src="images/leftarrow.gif" class="prevnext" alt="'
 . $translations['Previous'] . '" /></a>
      <a title="' . $translations['Next'] . '" class="next" href="' . $next_url
 . '"><img src="images/rightarrow.gif" class="prevnext" alt="'
 . $translations['Next'] . '" /></a>
      <div class="title">
        <span class="date">';
printf ( "%s, %s %d, %d", weekday_name ( strftime ( "%w", $time ) ),
  month_name ( $month - 1 ), $day, $year );
echo '</span><br />
      </div>
    </div><br />
    <form action="availability.php" method="post">
      ' . daily_matrix ( $date, $users ) . '
    </form>
    ' . print_trailer ( false, true, true );

?>
