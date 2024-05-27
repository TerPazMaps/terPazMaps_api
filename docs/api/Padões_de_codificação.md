# Padrões de codificação

## 1. Nomeação de Controladores
em `CamelCase` seguido do sufixo `Controller`.

- NomeDoControladorController.php   
Exemplo:`FeedbackActivitieController.php`

## 2. Nomeação de Models
em `CamelCase`

- NomeDoModel.php   
Exemplo:`Activitie.php`
  
## 3. Nomeação de Classes
em `CamelCase` e qual seu `tipo`

- NomeDaClasseSeeder.php
- NomeDaClassepolicy.php
  
Exemplo:`ActivitieSeeder.php`

## 4. Nomeação de chaves de Cache(redis)
concatenar com:
- nome do Controllador
- nome do método
- na necessidade de mais de uma chave cache no mesmo método, use um nome entre undeline.
- parâmetros de busca

exemplo:   
`$chaveCache = "ActivitieController_index . $request->regions;`

`$chaveCache = "ActivitieController_index_map_".$request->regions.$request->subclasses.$request->ids.$request->only_references;`

## 5. Padrões de respostas

1. success
```json
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": "Os dados devem estar dentro de DETAIL"
    }
}
```
1. Erro
```json
{
    "error": {
        "status": "422",
        "title": "Unprocessable Entity",
        "detail": "aqui deve ser apresentadas as mensagens sobre o erro"
    }
}
```
## 5. Rotas API
- caminhos base devem ser separados por `-`
- nomes de função personalizados devem ser em inglês e CamelCase   
`Route::get('/services/length-street', [ServicesController::class, 'getlengthStreet']);` 



[Voltar a pagina principal](/README.md)
