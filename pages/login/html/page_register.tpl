{block name="title" prepend}{"Register"}{/block}
{block name="content"}
<h1>Register</h1>
<div>
	<form method="post" action="index.php?page=register" data-action="index.php?page=register">
		<input type="hidden" value="send" name="mode">

		<div class="divRow">
			<label for="playername">Choose Username:</label>
			<input type="text" class="input" name="playername" id="playername" maxlenght="32">
			<span class="inputDesc">Max. 32 chars</span>
		</div>
		<div class="divRow">
			<label for="password">Choose Password:</label>
			<input type="password" class="input" name="password" id="password">
			<span class="inputDesc">Enter a password</span>
		</div>
		<div class="divRow">
			<label for="passwordReplay">Re-enter Password:</label>
			<input type="password" class="input" name="passwordReplay" id="passwordReplay">
			<span class="inputDesc">Dont mess this up</span>
		</div>
		<div class="divRow">
			<label for="email">Email:</label>
			<input type="email" class="input" name="email" id="email">
			<span class="inputDesc">A valid email is required</span>
		</div>
		<div class="divRow">
			<label for="emailReplay">Re-enter email:</label>
			<input type="email" class="input" name="emailReplay" id="emailReplay">
			<span class="inputDesc">(I'm not joking, put in a valid email)</span>
		</div>
		<div class="divRow">
			<label for="rules">Read the rules</label>
			<input type="checkbox" name="rules" id="rules" value="1">
			<span>I have read and agreed to the rules</span>
		</div>
		<div style="text-align: center;">
			<div id="recaptcha_widget" style="margin: auto;">
				<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k={$recaptchaPublicKey}"></script>
			</div>
		</div>
		<br>
		<br>
		<br>
		<br>
		<div class="divRow">
			<input type="submit" class="submitButton" value="Register">
		</div>
	</form>
</div>
{/block}
{block name="script" append}
<link rel="stylesheet" type="text/css" href="resources/css/register.css">
<script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
<script type="text/javascript">
	var recaptchaPublicKey = "{$recaptchaPublicKey}";
</script>
{/block}
