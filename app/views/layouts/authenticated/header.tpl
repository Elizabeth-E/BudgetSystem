<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<script src='https://www.google.com/recaptcha/api.js'></script>

	<link href="{$www}/css/bootstrap.min.css" rel="stylesheet">
	<link href="{$www}/css/fontawesome.min.css" rel="stylesheet">
	<link href="{$www}/css/all.css" rel="stylesheet">
	<link href="{$www}/css/style.css" rel="stylesheet">
	{* <link href="{$www}/css/darktheme.css" rel="stylesheet"> *}

	<script src="{$www}/js/jquery-3.3.1.min.js"></script>

	<title>{$title}</title>
</head>

<body>
	{include file="{$layout}\\menu.tpl"}

	<div id="wrapper">
		<div id="sidebar-wrapper">
			<ul class="sidebar-nav">
				<li class="indent"><a href="{$www}/user/profile"><i class="far fa-id-card fa-2x" aria-hidden="true"></i>   Profile</a></li>
				<li><a href="{$www}/accounts/accountOverview"><i class="fas fa-wallet fa-2x" aria-hidden="true"></i>   My Accounts</a></li>
				<li><a href="{$www}/dashboard/dashboardOverview"><i class="fas fa-tachometer-alt fa-2x" aria-hidden="true"></i>   Dashboard</a></li>
				<li class="indent"><a href="{$www}/bills/billsOverview"><i class="fas fa-landmark fa-2x" aria-hidden="true"></i>   Bills</a></li>
				<li class="indent"><a href="{$www}/savings/savingsOverview"><i class="fas fa-piggy-bank fa-2x" aria-hidden="true"></i>   Savings</a></li>
			</ul>
		</div>