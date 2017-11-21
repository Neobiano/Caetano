function mascara_data(campo, separador){
	var conteudo, tamanho, campo_formatado;
	conteudo = campo.value;
	tamanho = conteudo.length;
	campo_formatado = "";
	if(separador == null) separador = "/";
	
	for(x=0;x<tamanho;x++){
		if(x == 0){ // 0
			if (conteudo[x] >=0 && conteudo[x] <= 3){
				campo_formatado = campo_formatado+conteudo[x];
			} else{
				campo.value = campo_formatado;
				return;
			}			
		}
		
		if(x == 1){ // 1
			if (conteudo[x] >=0 && conteudo[x] <= 9){
				if(conteudo[0] == 3 && conteudo[x] > 1){
					campo.value = campo_formatado;
					return;
				}
				campo_formatado = campo_formatado+conteudo[x]+separador;
				x++;
				continue;
			} else{
				campo.value = campo_formatado;
				return;
			}
		}
		
		if(x == 2 || x == 5){ // 2, 5
			if (conteudo[x] == separador){
				campo_formatado = campo_formatado+conteudo[x];
			} else{
				campo.value = campo_formatado;
				return;
			}
		}
		
		if(x == 3){ // 3
			if (conteudo[x] >=0 && conteudo[x] <= 1){
				campo_formatado = campo_formatado+conteudo[x];
			} else{
				campo.value = campo_formatado;
				return;
			}
		}
		
		if(x == 4 || (x >= 6 && x <= 9)){ // 4, 6, 7, 8, 9
			if (conteudo[x] >=0 && conteudo[x] <= 9){
				if(x != 4) campo_formatado = campo_formatado+conteudo[x];
				else{
					campo_formatado = campo_formatado+conteudo[x]+separador;
					x++;
					continue;
				}
			} else{
				campo.value = campo_formatado;
				return;
			}
		}
	}	
	campo.value = campo_formatado;
}