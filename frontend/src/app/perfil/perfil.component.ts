import { Component, OnInit } from '@angular/core';
import { UsuarioService } from '../shared/services/usuario.service';
import { CadastroService } from '../cadastro/cadastro.service';

@Component({
	selector: 'app-perfil',
	templateUrl: './perfil.component.pug',
	styleUrls: ['./perfil.component.scss']
})
export class PerfilComponent implements OnInit {

	private editing: boolean = false;
	private usuario: any;

	constructor(private usuarioService: UsuarioService, private cadastroService: CadastroService) {

		this.usuario = this.usuarioService.getUsuario();

	}

	ngOnInit() {

		this.getUsuarioAdministradorTeste();
	}

	isEditing(): boolean {

		return this.editing;

	}

	setIsEditing(editing: boolean) {

		this.editing = editing;

	}

	getUsuarioAdministradorTeste() {

		this.cadastroService.findAdministrador().subscribe((response) => console.log(response));

	}

}
