<?php
{
  // $Header: /cvsroot/tsheet/timesheet.php/admin.php,v 1.2 2003/10/26 22:59:19 vexil Exp $

	require_once('auth-functions.php');
	if (!auth(5))
		Header("Location: login.php");

	// Connect to database.
	$dbh = dbConnect();
	$context_user = strtolower($_SESSION['context_user']);

  //define the command menu
/*	//$commandmenu->addCommand("admin.php", "Admin Homepage");
	$commandmenu->addCommand("client_maint.php", "Clients");
	$commandmenu->addCommand("user_maint.php", "Users");
	$commandmenu->addCommand("proj_maint.php", "Projects");
	$commandmenu->addCommand("task_maint.php", "Tasks");
	$commandmenu->addCommand("print_report.php", "Report");
	$commandmenu->addCommand("config.php", "Configuration");
	$commandmenu->addCommand("logout.php", "Logout");*/

  include "menu.inc";

  // Set default months
  if (!$month) $month = (int)date('m');
  if (!$year) $year = (int)date('Y');

  // Calculate the previous month.
  $next_month = $month + 1;
  $next_year = $year;
  $prev_month = $month - 1;
  $prev_year = $year;
  if (!checkdate($next_month, 1, $next_year)) {
    $next_month -= 12;
    $next_year ++;
  }
  if (!checkdate($prev_month, 1, $prev_year)) {
    $prev_month += 12;
    $prev_year --;
  }
}
?>
<html>
<head><title>Project Scheduler Administration Page</title>
<?php include ("header.inc"); ?>
</head>
<body <?php include ("body.inc"); ?> >
<?php include ("banner.inc"); ?>
	<p>
	<table width="90%" align="center" border="0" cellpadding="0" cellspacing="0" class="outer_table">
		<tr>
			<td>
				<table width="100%" border="0" class="table_head">
					<tr>
						<td align="left" nowrap class="outer_table_heading">
			  			<?php echo date('F Y',mktime(0,0,0,$month,1,$year)) ?>
						</td>
						<td align="right" nowrap>
						<?php
							print "<a href=\"$PHP_SELF?uid=$uid&month=$prev_month&year=$prev_year\" class=\"outer_table_action\">Prev</a>&nbsp;";
			    		print "<a HREF=\"$PHP_SELF?uid=$uid&month=$next_month&year=$next_year\" class=\"outer_table_action\">Next</a>";
						?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0" cellpadding="1" cellspacing="2" class="table_body">
					<tr class="inner_table_head">
						<td class="inner_table_column_heading">Name</td>
						<td class="inner_table_column_heading"><a href="<?php echo $PHP_SELF?>?orderby=username&month=<?php echo $month; ?>&year=<?php echo $year; ?>" class="inner_table_column_heading">Username</a></td>
						<td class="inner_table_column_heading">Hours</td>
						<td class="inner_table_column_heading">
							<b><a href="<?php echo $PHP_SELF?>?orderby=<?php echo $PROJECT_TABLE; ?>.proj_id&month=<?php echo $month; ?>&year=<?php echo $year; ?>" class="inner_table_column_heading">Project</a>&nbsp;/&nbsp;
							<a href="<?php echo $PHP_SELF?>?orderby=<?php echo $TASK_TABLE; ?>.task_id&month=<?php echo $month; ?>&year=<?php echo $year; ?>" class="inner_table_column_heading">Task</a></b>
						</td>
					</tr>
<?php {

  if ($orderby == '') $orderby="username";

  $query = "SELECT distinct first_name, last_name, $USER_TABLE.username, $PROJECT_TABLE.title, $PROJECT_TABLE.proj_id, ".
					 "$TASK_TABLE.name, $TASK_TABLE.task_id ".
		"FROM $USER_TABLE, $PROJECT_TABLE, $TASK_TABLE, $ASSIGNMENTS_TABLE, $TASK_ASSIGNMENTS_TABLE WHERE ".
    "$ASSIGNMENTS_TABLE.proj_id = $PROJECT_TABLE.proj_id AND $TASK_ASSIGNMENTS_TABLE.task_id = $TASK_TABLE.task_id ".
		"AND $PROJECT_TABLE.proj_id = $TASK_TABLE.proj_id AND ".
    "$ASSIGNMENTS_TABLE.username = $USER_TABLE.username AND $USER_TABLE.username NOT IN ('admin','guest') ORDER BY $orderby;";

  list ($qh,$num) = dbQuery($query);
  $last_username = "";

  while ($name_data = dbResult($qh))
	{
    $query = "SELECT sec_to_time(sum(unix_timestamp(end_time) - unix_timestamp(start_time))) AS diff FROM $TIMES_TABLE WHERE ".
      "start_time >= '$year-$month-1' AND end_time < '$next_year-$next_month-1' AND end_time > 0 ".
      "END uid='$name_data[username]' AND task_id=$name_data[task_id] AND proj_id=$name_data[proj_id]";

    list($qh2, $num2) = dbQuery($query);
    if ($num2 > 0)
      $time_data = dbResult($qh2);

    print "<TR>\n";

    if ($last_username != $name_data[username])
    {
			$last_username = $name_data[username];
			print "<TD>${name_data[first_name]} ${name_data[last_name]}</TD>\n";
			print "<TD><A HREF=\"admin_time_info.php?uid=${name_data[username]}&month=$month&year=$year\">${name_data[username]}</A></TD>\n";
    }
    else
    {
			print "<TD COLSPAN=2>&nbsp;</TD>\n";
    }
    if ($num2 > 0)
      print "<TD ALIGN=CENTER><TT>${time_data[diff]}</TT></TD>\n\n";
    else
      print "<TD ALIGN=CENTER><TT>00:00:00</TT></TD>\n";

    print "<TD><A HREF=\"javascript:void(0)\" ONCLICK=window.open(\"proj_info.php?proj_id=$name_data[proj_id]\",\"Info\",\"location=0,directories=no,status=no,menubar=no,resizable=1,scrollbar=yes,width=580,height=200\")>$name_data[title]</A> ".
          "<A HREF=\"javascript:void(0)\" ONCLICK=window.open(\"task_info.php?proj_id=$name_data[proj_id]&task_id=$name_data[task_id]\",\"TaskInfo\",\"location=0,directories=no,status=no,scrollbar=yes,menubar=no,resizable=1,width=580,height=220\")>$name_data[name]</A></TD>\n";
    print "</TR>\n";
  }
}
?>
				</TABLE>
			</td>
		</tr>
	</table>
<?
include ("footer.inc");
?>
</BODY>
</HTML>
