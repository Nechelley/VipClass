import { Component, OnInit } from '@angular/core';
import { NavegacaoService } from '../shared/services/navegacao.service';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import * as _ from 'lodash';
import { CadastroService } from './cadastro.service';
import { sexo } from '../app.constants';
import { AlertService } from '../shared/services/alert.service';

@Component({
	selector: 'app-cadastro',
	templateUrl: './cadastro.component.pug',
	styleUrls: ['./cadastro.component.scss']
})
export class CadastroComponent implements OnInit {

	formulario: FormGroup;
	sexo: any = sexo;

	constructor(
		private redirectService: NavegacaoService,
		private formBuilder: FormBuilder,
		private cadastroService: CadastroService,
		private alertService: AlertService
	) { }

	ngOnInit() {

		this.loadForm();

	}

	loadForm() {

		this.formulario = this.formBuilder.group({
			nome: [null, [Validators.required, Validators.maxLength(45)]],
			cpf: [null, Validators.required],
			sexo: [null, Validators.required],
			email: [null, Validators.required],
			senha: [null, [Validators.required, Validators.minLength(10)]],
			senhaRedigitada: [null, [Validators.required, Validators.minLength(10)]],
		});

	}

	goToLogin() {

		this.redirectService.redirectToLogin();

	}

	goToCadastroProfessor() {

		// TODO redirecionar para o cadastro de professor

	}

	senhasConferem(): boolean {

		return this.formulario.get('senha').value === this.formulario.get('senhaRedigitada').value && this.formulario.get('senhaRedigitada').value &&
			this.formulario.get('senhaRedigitada').valid && this.formulario.get('senha').valid;


	}

	cadastrarUsuario() {

		this.cadastroService.cadastrarAdministrador(this.formulario.value).subscribe(
			(response) => {

				if(response.status) {

					this.alertService.showAlert('Administrador cadastrado com sucesso!','success');

				} else {

					this.alertService.showAlert('Erro ao cadastrar administrador!','error');

				}

			}
		);

	}

}
