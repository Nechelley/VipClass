import { Injectable } from '@angular/core';
import { HttpCustomizedService } from '../shared/services/http-customized.service';
import { api, ACAO } from '../app.constants';
import { Observable } from 'rxjs';
import { UsuarioService } from '../shared/services/usuario.service';

@Injectable({
	providedIn: 'root'
})
export class CadastroService {

	constructor(private http: HttpCustomizedService, private usuarioService: UsuarioService) { }

	cadastrarAdministrador(admin: any): Observable<any> {

		admin.acao = ACAO.INSERT_UPDATE;

		return this.http.postWithCustomConfig(api.ADMINISTRADOR, admin, 'application/json', 'text');

	}

	findAdministrador(): Observable<any> {

		const user = this.usuarioService.getUsuario();
		user.acao = ACAO.GET;

		return this.http.postWithCustomConfig(api.ADMINISTRADOR, user, 'application/json', 'text');

	}

	updateAdministrador(admin: any): Observable<any> {

		admin.acao = ACAO.INSERT_UPDATE;
		return this.http.postWithCustomConfig(api.ADMINISTRADOR, admin, 'application/json', 'text');

	}
}
