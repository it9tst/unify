<div class="admin_login">
    <a style="">
        <div class="logo_login"></div>
    </a>
    <span class="text">Admin Panel</span>
	<form action="/login/" method="post" target="_self">
		<div class="user">
			Username <input type="text" name="email">
		</div>
		<div class="pass">
			Password<input type="password" name="pass">
		</div>
        <div style="margin: 10px 0px; color:red;"> <?php if(isset($this->error)) echo $this->error;?></div>
		<div class="button">
			<button type="submit" class="btn btn-dark" name="submit" value="accedi">Accedi</button>
		</div>
	</form>
</div>
