{block name="title" prepend}{"Home"}{/block}
{block name="content"}
	<section>
		<h1 style="font-size: 22px; color: red;">{$gameName}</h1>
	</section>
	<section>
		<div class="pageBox" style="width: 300px;">
			<h2>Login</h2>
			{if $code}
				<span style='color: red'>{$code}</span>
			{/if}
			<form id="login" name="login" action="index.php?page=login" data-action="index.php?page=login" method="post">
				<div class="row">
					<label for="playername">Username:</label>
					<input name="playername" id="playername" type="text">
				</div>
				<div class="row">
					<label for="password">Password:</label>
					<input name="password" id="password" type="password">
				</div>
				<div class="row">
					<input type="submit" value="Enter">
				</div>
			</form>
			<br>
			<span class="small">{$loginInfo}</span>
			<a href='index.php?page=verify&i=2&k=aaa'>dev link</a>
			<br>
			<a href="index.php?page=register"><b>Register</b></a>
		</div>
	</section>
{/block}