<!DOCTYPE html>
<html>
<head>
	<title>Leitura de Dados</title>
	<meta charset="utf-8">

	<style type="text/css">
		table, th, td {
			border-style: solid;
			border-width: 1px;
			padding: 4px;
			text-align: center;
		}
	</style>	 
</head>
<?php require_once ("menu.php"); ?>
<body onload="readPessoas()"> <!--ao final do carregamento da página, faz a leitura do banco de dados-->
	<h1>Leitura de Dados</h1>

	<table id="tabela">
		
	</table>
</body>
<script>
		function readPessoas() {//função para leitura de pessoas, utiliza ajax para consultar os dados do Banco
			document.getElementById("tabela").innerHTML = "";
			var xhttp;
			xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var response = JSON.parse(this.responseText);
					//console.log(response)
					//Criando tabelas HTML para inserir os dados dinamicamente
					var table = "<tr><th>Nome</th><th>Idade</th><th>Sexo</th><th>Estado Civil</th><th>Salvar</th><th>Deletar</th></tr>";
					var newLine = ""; 
					for (var i = response.length - 1; i >= 0; i--) {
						//montando linhas da tabela para inclusão de dados dinamicamente 
						newLine += "<tr><td hidden=\"true\"><p>"+response[i].id+"</p></td><td ondblclick=\"includeEvents(this)\"><p>"+response[i].nome+"</p></td><td ondblclick=\"includeEvents(this)\"><p>"+response[i].idade+"</p></td><td ondblclick=\"includeEvents(this)\"><p>"+response[i].sexo+"</p></td><td ondblclick=\"includeEvents(this)\"><p>"+response[i].estado_civil+"</p></td><td ondblclick=\"includeEvents(this)\"><a href=\"#\" id=\"save\" onclick=\"readPessoas()\"><img src=\"img/save.gif\" width=\"32px\" height=\"32px\"></a></td><td><a href=\"#\" onclick=\"deletar(this)\" id=\"delete\"><img src=\"img/delete.png\" width=\"32px\" height=\"32px\"></a></td></tr>"    					
					}
					table += newLine;
					document.getElementById("tabela").innerHTML = table; //incluindo a tabela na página
				}
			};
			xhttp.open("GET", "operations.php?q=readPessoas", true);
			xhttp.send();   
		}

		function includeEvents(elemento){//tratamento do clique duplo nos elementos para edição dos dados
			console.log(elemento)
			elemento.children[0].setAttribute("contenteditable", "true");
			elemento.children[0].setAttribute("onblur", "salva(this)");
		}

		function salva(elemento){ // cria método ajax para fazer o update no banco de dados
			elemento.setAttribute("contenteditable", "false");
			//console.log(elemento.parentElement.parentElement);
			//Recupera e envia todos os dados da linha
			var line = elemento.parentElement.parentElement;
			var id = line.children[0].children[0].innerHTML;
			var nome = line.children[1].children[0].innerHTML;
			var idade = line.children[2].children[0].innerHTML;
			var sexo = line.children[3].children[0].innerHTML;
			var estado_civil = line.children[4].children[0].innerHTML;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					window.alert(""+ this.responseText);
				}
			}
			xhttp.open("GET", "operations.php?q=update&id="+id+"&nome="+nome+"&idade="+idade+"&sexo="+sexo+"&estado_civil="+estado_civil, true);
			xhttp.send();   
		}

		//Deleta o registro no banco de dados
		function deletar(elemento){
			var xhttp = new XMLHttpRequest();
			var line = elemento.parentElement.parentElement;
			var id = line.children[0].children[0].innerHTML; // somente é necessário a recuperação do ID.
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("tabela").innerHTML = "";
					window.alert(""+ this.responseText);
					readPessoas();
				}
			}
			xhttp.open("GET", "operations.php?q=delete&id="+id, true);
			xhttp.send();  
		}
	</script>
</html>