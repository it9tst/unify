<div class="title_page_1 account_title">
    <span class="info_text">Informazioni account</span>
    <button id="buttonModifica" class="btn btn_rounded" type="button"><i class='fas fa-wrench'></i> Modifica</button>
</div>
<form class="page_account_box">
    <div class="form-row img_art">
		<img id="loaded_image" width="200" height="200" src="/images/accounts/<?php echo ($this->account->getFoto()!="")? $this->account->getFoto():"defaultAccount.jpg"?>">
		<div class="form-group">
			<input type="file" accept="image/jpg, image/jpeg ,image/png" class="form-control-file" id="inputAccountImage" style="display:none">
		</div>
	</div>

    <div class="form-row">
        <div class="col">
            <label for="usernameAccount">Username</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupPrepend3">@</span>
                </div>
                <input type="text" class="form-control" id="usernameAccount" value="<?php echo ($this->account->getNickname()!="")? $this->account->getNickname():"Username"?>" aria-describedby="inputGroupPrepend3" disabled>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="emailAccount">Email</label>
                <input type="email" class="form-control" id="emailAccount" value="<?php echo ($this->account->getEmail()!="")? $this->account->getEmail():"E-mail"?>" value="" disabled>
            </div>
        </div>
    </div>
	<div class="form-row">
        <div class="col">
            <div class="form-group">
                <label for="validationServerPass">Nuova Password</label>
                <input type="password" id="validationServerPass" class="form-control" aria-describedby="passwordHelpBlock" placeholder="Password" disabled>
                <small id="passwordHelpBlock" class="form-text invalid-feedback">
                    La password deve contenere da 8 a 20 caratteri, tra cui lettere e numeri, e non deve contenere caratteri speciali, spazi o emoji.
                </small>
            </div>
        </div>
        <div class="col">
            <label for="validationServerConfPass">Conferma Nuova Password</label>
            <input type="password" class="form-control" id="validationServerConfPass" placeholder="Conferma Password" value="" disabled>
            <div class="invalid-feedback">
                Per favore scegli una password.
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <label for="nomeAccount">Nome</label>
            <input type="text" class="form-control" id="nomeAccount" placeholder="Nome" value="<?php echo ($this->account->getNome()!="")? $this->account->getNome():""?>" disabled>
			<small id="passwordHelpBlock" class="form-text invalid-feedback">
                    Nome non valido
            </small>
		</div>
        <div class="col">
            <label for="cognomeAccount">Cognome</label>
            <input type="text" class="form-control" id="cognomeAccount" placeholder="Cognome" value="<?php echo ($this->account->getCognome()!="")? $this->account->getCognome():""?>" disabled>
			<small id="passwordHelpBlock" class="form-text invalid-feedback">
                    Cognome non valido
            </small>
		</div>
    </div>

    <div class="form-row">
        <div class="col">
            <label for="validationServer04">Paese</label>
            <select class="form-control" required disabled>
                <option value="<?php echo ($this->account->getPaese()["code"]!="")? $this->account->getPaese()["code"]:""?>"><?php echo ($this->account->getPaese()["name"]!="")? $this->account->getPaese()["name"]:"Paese"?></option>
                <?php require UTILS."utils.php";?>
				<?php foreach($countries as $k=>$v):?>
				<?php if($k==$this->account->getPaese()["code"])continue;?>
					<option value="<?php echo $k;?>"><?php echo $v;?></option>
				<?php endforeach;?>
            </select>
            <div class="invalid-feedback">
                Perfavore inserisci uno stato valido
            </div>
        </div>
        <div class="col">
            <label for="validationServer05">Cellulare</label>
            <input type="text" class="form-control" id="cellulareAccount" placeholder="Cellulare" value="<?php echo ($this->account->getCellulare()!="")? $this->account->getCellulare():""?>" required disabled>
            <div class="invalid-feedback">
                Per favore inserisci un numero di cellulare valido nel formato: '+Prefisso Numero' es. +39 3211234567
            </div>
        </div>
    </div>


    <div class="form-row data_nascita_register_box">
        <label for="validationServerDay">Data di nascita</label>
        <div class="data_nascita_register">
            <div class="giorno_register">
                <input type="number" class="form-control" id="validationServerDay" placeholder="Giorno" value="<?php echo intval(date("d",strtotime($this->account->getDataNascita())));?>" min="1" max="31" disabled>

            </div>
            <div class="mese_register">
                <select class="form-control" disabled>
                    <option value="<?php echo date("m",strtotime($this->account->getDataNascita()));?>" selected disabled hidden>
						<?php
							switch(date("m",strtotime($this->account->getDataNascita()))){
								case 1: echo "Gennaio";		break;
								case 2: echo "Febbraio";	break;
								case 3: echo "Marzo";		break;
								case 4: echo "Aprile";		break;
								case 5: echo "Maggio";		break;
								case 6: echo "Giugno";		break;
								case 7: echo "Luglio";		break;
								case 8: echo "Agosto";		break;
								case 9: echo "Settembre";	break;
								case 10: echo "Ottobre";	break;
								case 11: echo "Novembre";	break;
								case 12: echo "Dicembre";	break;
							}
						?>
					</option>
                    <option value="1">Gennaio</option>
                    <option value="2">Febbraio</option>
                    <option value="3">Marzo</option>
                    <option value="4">Aprile</option>
                    <option value="5">Maggio</option>
                    <option value="6">Giugno</option>
                    <option value="7">Luglio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Settembre</option>
                    <option value="10">Ottobre</option>
                    <option value="11">Novembre</option>
                    <option value="12">Dicembre</option>
                </select>
            </div>

            <div class="anno_register">
                <input type="number" class="form-control" id="validationServerYear" placeholder="Anno" value="<?php echo date("Y",strtotime($this->account->getDataNascita()));?>" min="<?php echo intval(date("Y"))-108;?>" max="<?php echo intval(date("Y"))-16;?>" disabled>

            </div>
        </div>
        <div class="invalid-feedback">
                    Per registrarti devi aver compiuto 16 anni in base al Regolamento Europeo in materia di Protezione dei Dati Personali (GDPR).
        </div>
    </div>
</form>
