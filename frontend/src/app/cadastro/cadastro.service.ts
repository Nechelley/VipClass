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

		return this.http.postWithTextResponse(api.ADMINISTRADOR, admin);

	}

	cadastrarProfessor(professor: any): Observable<any> {

		professor.acao = ACAO.INSERT_UPDATE;

		return this.http.postWithTextResponse(api.PROFESSOR, professor);

	}

	cadastrarAluno(aluno: any): Observable<any> {

		aluno.acao = ACAO.INSERT_UPDATE;

		return this.http.postWithTextResponse(api.ALUNO, aluno);

	}

	findAdministrador(): Observable<any> {

		const user = this.usuarioService.getUsuario();
		user.acao = ACAO.GET;

		return this.http.postWithTextResponse(api.ADMINISTRADOR, user);

	}

	updateAdministrador(admin: any): Observable<any> {

		admin.acao = ACAO.INSERT_UPDATE;
		return this.http.postWithTextResponse(api.ADMINISTRADOR, admin);

	}
}
