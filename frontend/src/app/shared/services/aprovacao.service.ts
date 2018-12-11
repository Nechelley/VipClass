import { Injectable } from '@angular/core';
import { HttpCustomizedService } from './http-customized.service';
import { api, ACAO } from 'src/app/app.constants';
import { Observable } from 'rxjs';
import { UsuarioService } from './usuario.service';

@Injectable({
	providedIn: 'root'
})
export class AprovacaoService {

	constructor(
		private http: HttpCustomizedService,
		private usuarioService: UsuarioService
	) { }

	findProfessoresNaoAprovados(): Observable<any> {

		const data = {
			acao: ACAO.GET,
			naoAprovados: true
		}

		return this.http.postWithTextResponse(api.PROFESSOR, data);

	}

	findCursosNaoAprovados() {



	}

	aprovarProfessor(professor: any): Observable<any> {

		const data = {
			acao: ACAO.APROVAR_PROFESSOR,
			administradorId: this.usuarioService.getUsuario().id,
			professorId: professor.id
		}

		return this.http.postWithTextResponse(api.ADMINISTRADOR, data);

	}

	aprovarCurso(curso: any) {

		return this.http.postWithTextResponse(api.ADMINISTRADOR, curso);

	}

}
