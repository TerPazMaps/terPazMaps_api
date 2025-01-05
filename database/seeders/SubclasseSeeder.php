<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubclasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     $subclasses = [
    //         ['id' => 1, 'classe_id' => 1, 'descricao' => 'Venda de chope e similares', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 2, 'classe_id' => 1, 'descricao' => 'Venda de carvão', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 3, 'classe_id' => 1, 'descricao' => 'Loja de variedades', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 4, 'classe_id' => 2, 'descricao' => 'Reparação/manutenção de equipamentos eletroeletrônicos', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 5, 'classe_id' => 1, 'descricao' => 'Venda de carne (frango)', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 6, 'classe_id' => 1, 'descricao' => 'Comércio/depósito de bebidas', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 7, 'classe_id' => 3, 'descricao' => 'Igreja evangélica', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 8, 'classe_id' => 1, 'descricao' => 'Venda de picolé, sorvete e similares', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 9, 'classe_id' => 1, 'descricao' => 'Minimercado ou mercearia', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 10, 'classe_id' => 4, 'descricao' => 'Carpintaria ou marcenaria', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 11, 'classe_id' => 2, 'descricao' => 'Manutenção de motores ou bombas', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 12, 'classe_id' => 4, 'descricao' => 'Metalurgia', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 13, 'classe_id' => 2, 'descricao' => 'Salão de beleza', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 14, 'classe_id' => 5, 'descricao' => 'Padaria ou confeitaria', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 15, 'classe_id' => 6, 'descricao' => 'Arena esportiva', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 16, 'classe_id' => 1, 'descricao' => 'Brechó', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 17, 'classe_id' => 7, 'descricao' => 'Escola particular', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 18, 'classe_id' => 1, 'descricao' => 'Comércio de gás', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 19, 'classe_id' => 1, 'descricao' => 'Vestuário e acessórios', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 20, 'classe_id' => 2, 'descricao' => 'Oficina mecânica (carros)', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 21, 'classe_id' => 1, 'descricao' => 'Venda de carne (açougue)', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 22, 'classe_id' => 8, 'descricao' => 'Assistência social', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 23, 'classe_id' => 5, 'descricao' => 'Vidros e cristais', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 24, 'classe_id' => 2, 'descricao' => 'Lava-jato', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 25, 'classe_id' => 6, 'descricao' => 'Praça', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 26, 'classe_id' => 1, 'descricao' => 'Venda de farinha', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 27, 'classe_id' => 9, 'descricao' => 'Residencial/vila', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 28, 'classe_id' => 1, 'descricao' => 'Venda de açaí', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 15:31:42'],
    //         ['id' => 29, 'classe_id' => 2, 'descricao' => 'Corte e costura', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 30, 'classe_id' => 7, 'descricao' => 'Escola municipal', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 31, 'classe_id' => 1, 'descricao' => 'Comércio de materiais de construção', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 32, 'classe_id' => 1, 'descricao' => 'Pizzaria', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 33, 'classe_id' => 1, 'descricao' => 'Restaurante', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 34, 'classe_id' => 1, 'descricao' => 'Comércio de peças para bicicletas', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 35, 'classe_id' => 1, 'descricao' => 'Comércio de plásticos e utilidades', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 36, 'classe_id' => 1, 'descricao' => 'Comércio de ferragens', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 37, 'classe_id' => 1, 'descricao' => 'Comércio de peças para motos', 'created_at' => '2021-09-23 12:04:35', 'updated_at' => '2021-09-23 12:04:35'],
    //         ['id' => 38, 'classe_id' => 1, 'descricao' => 'Loja de conveniência', 'created_at' => '2021-09-23 12:04:36', 'updated_at' => '2021-09-23 12:04:36'],
    //         ['id' => 39, 'classe_id' => 10, 'descricao' => 'Recuperação de sucata', 'created_at' => '2021-09-23 12:04:36', 'updated_at' => '2021-09-23 12:04:36'],
    //         ['id' => 40, 'classe_id' => 1, 'descricao' => 'Banca de jornais, revistas e similares', 'created_at' => '2021-09-23 12:04:36', 'updated_at' => '2021-09-23 12:04:36'],
           
    //         41	1	"Farmácia"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         42	2	"Reparação de panelas"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         43	9	"Aluguel de kitnet"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         44	7	"Escola estadual"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         45	1	"Venda de churrasco"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         46	2	"Copiadora"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         47	2	"Reparação de aparelhos telefônicos (celular)"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         48	1	"Peixaria"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         49	1	"Bar"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         50	2	"Oficina mecânica (motos)"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         51	2	"Festas e eventos"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         52	3	"Templo/terreiro da Umbanda ou Candomblé"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         53	6	"Academia de musculação/ginástica"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         54	11	"Aparelhos de refrigeração, extintores e similares"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         55	1	"Pet shop"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         56	1	"Papelaria"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         57	1	"Comércio de aparelhos e jogos eletrônicos"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         58	2	"Relojoeiro"		"2021-09-23 12:04:36"	"2021-09-28 09:12:48"
    //         59	7	"Aula de reforço"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         60	1	"Lanchonete"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         61	2	"Oficina de bicicletas"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         62	1	"Meio-a-meio"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         63	1	"Comércio de eletrodomésticos, móveis e artigos de uso doméstico"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         64	3	"Igreja católica"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         65	12	"Laboratório"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         66	12	"Dentista"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         67	2	"Serviço de pintura (estamparia)"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         68	2	"Manicure e pedicure"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         69	10	"Estação de tratamento de água ou esgoto"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         70	1	"Venda de gelo e similares"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         71	1	"Fruteira"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         72	1	"Comércio de produtos naturais"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         73	1	"Pastelaria"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         74	12	"Posto de saúde"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         75	7	"Creche"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         76	8	"Associação ou conselho"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         77	1	"Venda de comidas típicas"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         78	2	"Oficina de motos e bicicletas"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         79	1	"Comércio de hortifrutigranjeiros"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         80	13	"Empresa ou cooperativa de transporte"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         81	1	"Venda de suco, doce ou polpa de frutas"		"2021-09-23 12:04:36"	"2021-09-23 12:04:36"
    //         82	1	"Mercado"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         83	1	"Cafeteria"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         84	12	"Policlínica"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         85	2	"Motel"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         86	1	"Ótica"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         87	1	"Venda de carne (mariscos)"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         88	1	"Comércio de artigos esportivos e suplementos"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         89	2	"Delegacia de Polícia"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         90	1	"Comércio de doces, balas, bombons e similares"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         92	6	"Clubes esportivos e similares"		"2021-09-23 12:04:37"	"2021-09-27 21:38:02"
    //         93	2	"Sapateiro"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         94	2	"Chaveiro"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         95	11	"Comércio e manutenção de equipamentos e suprimentos de informática"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         96	13	"Ponto de moto-táxi"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         98	13	"Final de linha de ônibus/microonibus"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         99	7	"Escola de artes"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         100	1	"Floricultura"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         101	2	"Comunicação visual"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         102	2	"Reparação de móveis"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         103	13	"Ponto de ônibus/microonibus"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         104	5	"Artesanato"		"2021-09-23 12:04:37"	"2021-09-23 12:04:37"
    //         105	1	"Comércio de produtos cosméticos e de perfumaria"		"2021-09-23 12:04:38"	"2021-09-23 12:04:38"
    //         106	2	"Estúdio de fotografia"		"2021-09-23 12:04:38"	"2021-09-23 12:04:38"
    //         107	12	"Clínica (fisioterapia)"		"2021-09-23 12:04:38"	"2021-09-23 12:04:38"
    //         108	1	"Hamburgueria"		"2021-09-23 12:04:38"	"2021-09-23 12:04:38"
    //         109	1	"Comércio de produtos hospitalares"		"2021-09-23 12:04:38"	"2021-09-23 12:04:38"
    //         110	2	"Serviço de internet"		"2021-09-23 12:04:38"	"2021-09-23 12:04:38"
    //         111	2	"Escritório (contabilidade)"		"2021-09-23 12:04:38"	"2021-09-23 12:04:38"
    //         112	2	"Escritório (energia)"		"2021-09-23 12:04:38"	"2021-09-23 12:04:38"
    //         113	1	"Tapiocaria"		"2021-09-23 12:04:38"	"2021-09-23 12:04:38"
    //         114	1	"Venda de artigos usados"		"2021-09-23 12:04:38"	"2021-09-23 12:04:38"
    //         116	2	"Escritório (consultoria)"		"2021-09-23 12:04:38"	"2021-09-23 12:04:38"
    //         118	1	"Comércio de rolamentos"		"2021-09-23 12:43:04"	"2021-09-23 12:43:04"
    //         119	1	"Comércio de animais vivos (peixes)"		"2021-09-23 12:43:04"	"2021-09-23 12:43:04"
    //         120	6	"Campo de futebol"		"2021-09-23 12:43:04"	"2021-09-23 12:43:04"
    //         121	5	"Trabalhos em mármore, granito, ardósia e outras pedras"		"2021-09-23 12:43:04"	"2021-10-18 18:46:06"
    //         122	12	"Atividades de enfermagem"		"2021-09-23 12:43:05"	"2021-09-23 12:43:05"
    //         123	6	"Quadra poliesportiva"		"2021-09-23 12:43:05"	"2021-09-23 12:43:05"
    //         124	2	"Lotérica e similares"		"2021-09-23 12:43:05"	"2021-09-23 12:43:05"
    //         125	1	"Venda de chope, gelo e similares"		"2021-09-23 12:43:05"	"2021-09-23 12:43:05"
    //         126	1	"Venda de guaraná"		"2021-09-23 12:43:05"	"2021-09-23 12:43:05"
    //         128	2	"Abastecimento de água"	"#3771c8"	"2021-09-23 12:43:06"	"2021-09-23 16:39:35"
    //         129	2	"Comunicação (rádio ou TV)"		"2021-09-23 12:43:06"	"2021-09-23 12:43:06"
    //         130	1	"Comércio de inseticidas e similares"		"2021-09-23 12:43:06"	"2021-09-23 12:43:06"
    //         131	2	"Estúdio de tatuagem"		"2021-09-23 12:43:06"	"2021-09-23 12:43:06"
    //         132	4	"Serraria"		"2021-09-23 12:43:06"	"2021-09-23 12:43:06"
    //         133	1	"Comércio de brinquedos e artigos recreativos"		"2021-09-23 12:43:06"	"2021-09-23 12:43:06"
    //         134	2	"Escritório (advocacia)"		"2021-09-23 17:16:44"	"2021-09-23 17:16:44"
    //         135	7	"Universidade"		"2021-09-23 17:16:44"	"2021-09-23 17:16:44"
    //         136	1	"Posto de gasolina"		"2021-09-23 17:16:44"	"2021-09-23 17:16:44"
    //         137	1	"Comércio de discos, CDs, DVDs e fitas"		"2021-09-23 17:16:44"	"2021-09-23 17:16:44"
    //         138	3	"Centro espírita"		"2021-09-23 17:16:44"	"2021-09-23 17:16:44"
    //         139	1	"Comércio de peças para veículos automotores"		"2021-09-23 17:16:44"	"2021-09-23 17:16:44"
    //         140	1	"Supermercado"		"2021-09-23 17:16:45"	"2021-09-23 17:16:45"
    //         141	8	"Clube social ou cultural"		"2021-09-23 17:16:45"	"2021-09-23 17:16:45"
    //         142	2	"Hotel"		"2021-09-23 17:16:45"	"2021-09-23 17:16:45"
    //         143	12	"Hospital"		"2021-09-23 17:16:45"	"2021-09-23 17:16:45"
    //         144	1	"Feira"		"2021-09-23 17:16:46"	"2021-09-23 17:16:46"
    //         145	7	"Curso pré-vestibular ou profissionalizante"		"2021-09-23 17:16:46"	"2021-09-23 17:16:46"
    //         146	2	"Segurança e vigilância"		"2021-09-23 17:16:47"	"2021-09-23 17:16:47"
    //         147	2	"Funerária"		"2021-09-23 17:16:47"	"2021-09-23 17:16:47"
    //         149	13	"Ponto de táxi"		"2021-09-23 17:52:57"	"2021-09-23 17:52:57"
    //         150	2	"Escritório (arquitetura, urbanismo ou paisagismo)"		"2021-09-23 17:52:57"	"2021-09-27 21:41:07"
    //         151	1	"Comércio de velas, artigos religiosos e similares"		"2021-09-23 17:52:58"	"2021-09-23 17:52:58"
    //         152	2	"Escritório (revendedora)"		"2021-09-23 17:52:58"	"2021-09-23 17:52:58"
    //         153	13	"Porto/trapiche/atracadouro"		"2021-09-23 17:52:58"	"2021-09-23 17:52:58"
    //         154	1	"Comércio de madeiras"		"2021-09-23 17:52:58"	"2021-09-23 17:52:58"
    //         155	2	"Correios/agência postal"		"2021-09-23 17:52:58"	"2021-09-23 17:52:58"
    //         156	7	"Escola de música"		"2021-09-23 17:52:58"	"2021-09-23 17:52:58"
    //         157	2	"Administração pública"		"2021-09-23 17:52:58"	"2021-09-23 17:52:58"
    //         158	1	"Comércio de pilhas, baterias e similares"		"2021-09-23 17:52:58"	"2021-09-23 17:52:58"
    //         159	1	"Comércio de animais vivos (frangos)"		"2021-09-23 17:52:59"	"2021-09-23 17:52:59"
    //         160	12	"Clínica (veterinária)"		"2021-09-23 17:52:59"	"2021-09-23 17:52:59"
    //         161	1	"Concessionária de motos ou veículos automotores"		"2021-09-23 17:52:59"	"2021-09-23 17:52:59"
    //         162	1	"Recarga telefônica"		"2021-09-23 17:52:59"	"2021-09-23 17:52:59"
    //         163	13	"Estacionamento de veículos"		"2021-09-23 17:52:59"	"2021-09-23 17:52:59"
    //         164	2	"Escritório (planos de saúde)"		"2021-09-23 17:52:59"	"2021-09-23 17:52:59"
    //         165	2	"Ponto turístico"		"2021-09-23 17:53:00"	"2021-09-23 17:53:00"
    //         168	2	"Cemitério"		"2021-09-23 18:43:31"	"2021-09-23 18:43:31"
    //         169	14	"Hortas"		"2021-09-23 18:43:31"	"2021-09-23 18:43:31"
    //         170	7	"Escola em regime de convênio"		"2021-09-23 18:43:31"	"2021-09-23 18:43:31"
    //         171	2	"Serviço de pintura (faixas)"		"2021-09-23 18:53:12"	"2021-09-23 18:53:12"
    //         172	1	"Venda de tacacá"	"#ffd42a"	"2021-09-23 18:53:12"	"2021-10-19 18:15:43"
    //         173	2	"Agência bancária"		"2021-10-18 18:29:41"	"2021-10-18 18:29:41"
    //         174	13	"Garagem de ônibus/microonibus"		"2021-10-18 18:29:42"	"2021-10-18 18:29:42"
    //         175	2	"Estamparia"		"2021-10-18 18:29:43"	"2021-10-18 18:29:43"
    //         176	1	"Venda de maniçoba"		"2021-10-18 18:29:44"	"2021-10-18 18:29:44"
    //         177	1	"Venda e compra de ouro"		"2021-10-18 18:29:45"	"2021-10-18 18:29:45"
    //         178	10	"Cooperativa de catadores"		"2021-10-18 18:29:46"	"2021-10-18 18:29:46"
    //         179	2	"Aluguel de andaimes"		"2021-10-18 18:29:47"	"2021-10-18 18:29:47"
    //         180	2	"Escritório (energia solar)"		"2021-10-18 18:29:47"	"2021-10-18 18:29:47"
    //         181	2	"Agência de turismo"		"2021-10-18 18:29:47"	"2021-10-18 18:29:47"
    //         182	2	"Lavanderia"		"2021-10-18 18:29:47"	"2021-10-18 18:29:47"
    //         183	12	"Clínica (terapêutica)"		"2021-10-18 18:29:47"	"2021-10-18 18:29:47"
    //         184	1	"Comércio e exportação de madeiras"		"2022-01-18 19:21:58"	"2022-01-18 19:21:58"
    //         185	8	"Sindicato"		"2022-01-18 19:21:58"	"2022-01-18 19:21:58"
    //         ['id' => 186, 'classe_id' => 12, 'descricao' => 'Laboratório', 'created_at' => '2021-09-23 12:04:36', 'updated_at' => '2021-09-23 12:04:36'],
    //     ];

    //     DB::table('subclasses')->insert($subclasses);
    // }

    public function run(): void
    {
        $columns = ['id', 'class_id', 'name', 'related_color','created_at', 'updated_at'];
        $values = [
            [1,1,'Venda de chope e similares',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [2,1,'Venda de carvão',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [3,1,'Loja de variedades',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [4,2,'Reparação/manutenção de equipamentos eletroeletrônicos',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [5,1,'Venda de carne (frango)',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [6,1,'Comércio/depósito de bebidas',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [7,3,'Igreja evangélica',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [8,1,'Venda de picolé, sorvete e similares',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [9,1,'Minimercado ou mercearia',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [10,4,'Carpintaria ou marcenaria',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [11,2,'Manutenção de motores ou bombas',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [12,4,'Metalurgia',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [13,2,'Salão de beleza',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [14,5,'Padaria ou confeitaria',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [15,6,'Arena esportiva',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [16,1,'Brechó',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [17,7,'Escola particular',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [18,1,'Comércio de gás',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [19,1,'Vestuário e acessórios',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [20,2,'Oficina mecânica (carros)',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [21,1,'Venda de carne (açougue)',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [22,8,'Assistência social',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [23,5,'Vidros e cristais',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [24,2,'Lava-jato',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [25,6,'Praça',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [26,1,'Venda de farinha',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [27,9,'Residencial/vila',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [28,1,'Venda de açaí','#612f56','2021-09-23 12:04:35','2021-09-23 15:31:42'],
            [29,2,'Corte e costura',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [30,7,'Escola municipal',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [31,1,'Comércio de materiais de construção',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [32,1,'Pizzaria',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [33,1,'Restaurante',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [34,1,'Comércio de peças para bicicletas',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [35,1,'Comércio de plásticos e utilidades',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [36,1,'Comércio de ferragens',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [37,1,'Comércio de peças para motos',null,'2021-09-23 12:04:35','2021-09-23 12:04:35'],
            [38,1,'Loja de conveniência',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [39,10,'Recuperação de sucata',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [40,1,'Banca de jornais, revistas e similares',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [41,1,'Farmácia',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [42,2,'Reparação de panelas',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [43,9,'Aluguel de kitnet',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [44,7,'Escola estadual',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [45,1,'Venda de churrasco',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [46,2,'Copiadora',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [47,2,'Reparação de aparelhos telefônicos (celular)',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [48,1,'Peixaria',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [49,1,'Bar',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [50,2,'Oficina mecânica (motos)',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [51,2,'Festas e eventos',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [52,3,'Templo/terreiro da Umbanda ou Candomblé',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [53,6,'Academia de musculação/ginástica',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [54,11,'Aparelhos de refrigeração, extintores e similares',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [55,1,'Pet shop',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [56,1,'Papelaria',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [57,1,'Comércio de aparelhos e jogos eletrônicos',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [58,2,'Relojoeiro',null,'2021-09-23 12:04:36','2021-09-28 09:12:48'],
            [59,7,'Aula de reforço',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [60,1,'Lanchonete',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [61,2,'Oficina de bicicletas',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [62,1,'Meio-a-meio',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [63,1,'Comércio de eletrodomésticos, móveis e artigos de uso doméstico',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [64,3,'Igreja católica',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [65,12,'Laboratório',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [66,12,'Dentista',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [67,2,'Serviço de pintura (estamparia)',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [68,2,'Manicure e pedicure',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [69,10,'Estação de tratamento de água ou esgoto',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [70,1,'Venda de gelo e similares',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [71,1,'Fruteira',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [72,1,'Comércio de produtos naturais',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [73,1,'Pastelaria',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [74,12,'Posto de saúde',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [75,7,'Creche',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [76,8,'Associação ou conselho',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [77,1,'Venda de comidas típicas',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [78,2,'Oficina de motos e bicicletas',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [79,1,'Comércio de hortifrutigranjeiros',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [80,13,'Empresa ou cooperativa de transporte',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [81,1,'Venda de suco, doce ou polpa de frutas',null,'2021-09-23 12:04:36','2021-09-23 12:04:36'],
            [82,1,'Mercado',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [83,1,'Cafeteria',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [84,12,'Policlínica',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [85,2,'Motel',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [86,1,'Ótica',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [87,1,'Venda de carne (mariscos)',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [88,1,'Comércio de artigos esportivos e suplementos',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [89,2,'Delegacia de Polícia',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [90,1,'Comércio de doces, balas, bombons e similares',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [92,6,'Clubes esportivos e similares',null,'2021-09-23 12:04:37','2021-09-27 21:38:02'],
            [93,2,'Sapateiro',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [94,2,'Chaveiro',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [95,11,'Comércio e manutenção de equipamentos e suprimentos de informática',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [96,13,'Ponto de moto-táxi',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [98,13,'Final de linha de ônibus/microonibus',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [99,7,'Escola de artes',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [100,1,'Floricultura',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [101,2,'Comunicação visual',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [102,2,'Reparação de móveis',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [103,13,'Ponto de ônibus/microonibus',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [104,5,'Artesanato',null,'2021-09-23 12:04:37','2021-09-23 12:04:37'],
            [105,1,'Comércio de produtos cosméticos e de perfumaria',null,'2021-09-23 12:04:38','2021-09-23 12:04:38'],
            [106,2,'Estúdio de fotografia',null,'2021-09-23 12:04:38','2021-09-23 12:04:38'],
            [107,12,'Clínica (fisioterapia)',null,'2021-09-23 12:04:38','2021-09-23 12:04:38'],
            [108,1,'Hamburgueria',null,'2021-09-23 12:04:38','2021-09-23 12:04:38'],
            [109,1,'Comércio de produtos hospitalares',null,'2021-09-23 12:04:38','2021-09-23 12:04:38'],
            [110,2,'Serviço de internet',null,'2021-09-23 12:04:38','2021-09-23 12:04:38'],
            [111,2,'Escritório (contabilidade)',null,'2021-09-23 12:04:38','2021-09-23 12:04:38'],
            [112,2,'Escritório (energia)',null,'2021-09-23 12:04:38','2021-09-23 12:04:38'],
            [113,1,'Tapiocaria',null,'2021-09-23 12:04:38','2021-09-23 12:04:38'],
            [114,1,'Venda de artigos usados',null,'2021-09-23 12:04:38','2021-09-23 12:04:38'],
            [116,2,'Escritório (consultoria)',null,'2021-09-23 12:04:38','2021-09-23 12:04:38'],
            [118,1,'Comércio de rolamentos',null,'2021-09-23 12:43:04','2021-09-23 12:43:04'],
            [119,1,'Comércio de animais vivos (peixes)',null,'2021-09-23 12:43:04','2021-09-23 12:43:04'],
            [120,6,'Campo de futebol',null,'2021-09-23 12:43:04','2021-09-23 12:43:04'],
            [121,5,'Trabalhos em mármore, granito, ardósia e outras pedras',null,'2021-09-23 12:43:04','2021-10-18 18:46:06'],
            [122,12,'Atividades de enfermagem',null,'2021-09-23 12:43:05','2021-09-23 12:43:05'],
            [123,6,'Quadra poliesportiva',null,'2021-09-23 12:43:05','2021-09-23 12:43:05'],
            [124,2,'Lotérica e similares',null,'2021-09-23 12:43:05','2021-09-23 12:43:05'],
            [125,1,'Venda de chope, gelo e similares',null,'2021-09-23 12:43:05','2021-09-23 12:43:05'],
            [126,1,'Venda de guaraná',null,'2021-09-23 12:43:05','2021-09-23 12:43:05'],
            [128,2,'Abastecimento de água','#3771c8','2021-09-23 12:43:06','2021-09-23 16:39:35'],
            [129,2,'Comunicação (rádio ou TV)',null,'2021-09-23 12:43:06','2021-09-23 12:43:06'],
            [130,1,'Comércio de inseticidas e similares',null,'2021-09-23 12:43:06','2021-09-23 12:43:06'],
            [131,2,'Estúdio de tatuagem',null,'2021-09-23 12:43:06','2021-09-23 12:43:06'],
            [132,4,'Serraria',null,'2021-09-23 12:43:06','2021-09-23 12:43:06'],
            [133,1,'Comércio de brinquedos e artigos recreativos',null,'2021-09-23 12:43:06','2021-09-23 12:43:06'],
            [134,2,'Escritório (advocacia)',null,'2021-09-23 17:16:44','2021-09-23 17:16:44'],
            [135,7,'Universidade',null,'2021-09-23 17:16:44','2021-09-23 17:16:44'],
            [136,1,'Posto de gasolina',null,'2021-09-23 17:16:44','2021-09-23 17:16:44'],
            [137,1,'Comércio de discos, CDs, DVDs e fitas',null,'2021-09-23 17:16:44','2021-09-23 17:16:44'],
            [138,3,'Centro espírita',null,'2021-09-23 17:16:44','2021-09-23 17:16:44'],
            [139,1,'Comércio de peças para veículos automotores',null,'2021-09-23 17:16:44','2021-09-23 17:16:44'],
            [140,1,'Supermercado',null,'2021-09-23 17:16:45','2021-09-23 17:16:45'],
            [141,8,'Clube social ou cultural',null,'2021-09-23 17:16:45','2021-09-23 17:16:45'],
            [142,2,'Hotel',null,'2021-09-23 17:16:45','2021-09-23 17:16:45'],
            [143,12,'Hospital',null,'2021-09-23 17:16:45','2021-09-23 17:16:45'],
            [144,1,'Feira',null,'2021-09-23 17:16:46','2021-09-23 17:16:46'],
            [145,7,'Curso pré-vestibular ou profissionalizante',null,'2021-09-23 17:16:46','2021-09-23 17:16:46'],
            [146,2,'Segurança e vigilância',null,'2021-09-23 17:16:47','2021-09-23 17:16:47'],
            [147,2,'Funerária',null,'2021-09-23 17:16:47','2021-09-23 17:16:47'],
            [149,13,'Ponto de táxi',null,'2021-09-23 17:52:57','2021-09-23 17:52:57'],
            [150,2,'Escritório (arquitetura, urbanismo ou paisagismo)',null,'2021-09-23 17:52:57','2021-09-27 21:41:07'],
            [151,1,'Comércio de velas, artigos religiosos e similares',null,'2021-09-23 17:52:58','2021-09-23 17:52:58'],
            [152,2,'Escritório (revendedora)',null,'2021-09-23 17:52:58','2021-09-23 17:52:58'],
            [153,13,'Porto/trapiche/atracadouro',null,'2021-09-23 17:52:58','2021-09-23 17:52:58'],
            [154,1,'Comércio de madeiras',null,'2021-09-23 17:52:58','2021-09-23 17:52:58'],
            [155,2,'Correios/agência postal',null,'2021-09-23 17:52:58','2021-09-23 17:52:58'],
            [156,7,'Escola de música',null,'2021-09-23 17:52:58','2021-09-23 17:52:58'],
            [157,2,'Administração pública',null,'2021-09-23 17:52:58','2021-09-23 17:52:58'],
            [158,1,'Comércio de pilhas, baterias e similares',null,'2021-09-23 17:52:58','2021-09-23 17:52:58'],
            [159,1,'Comércio de animais vivos (frangos)',null,'2021-09-23 17:52:59','2021-09-23 17:52:59'],
            [160,12,'Clínica (veterinária)',null,'2021-09-23 17:52:59','2021-09-23 17:52:59'],
            [161,1,'Concessionária de motos ou veículos automotores',null,'2021-09-23 17:52:59','2021-09-23 17:52:59'],
            [162,1,'Recarga telefônica',null,'2021-09-23 17:52:59','2021-09-23 17:52:59'],
            [163,13,'Estacionamento de veículos',null,'2021-09-23 17:52:59','2021-09-23 17:52:59'],
            [164,2,'Escritório (planos de saúde)',null,'2021-09-23 17:52:59','2021-09-23 17:52:59'],
            [165,2,'Ponto turístico',null,'2021-09-23 17:53:00','2021-09-23 17:53:00'],
            [168,2,'Cemitério',null,'2021-09-23 18:43:31','2021-09-23 18:43:31'],
            [169,14,'Hortas',null,'2021-09-23 18:43:31','2021-09-23 18:43:31'],
            [170,7,'Escola em regime de convênio',null,'2021-09-23 18:43:31','2021-09-23 18:43:31'],
            [171,2,'Serviço de pintura (faixas)',null,'2021-09-23 18:53:12','2021-09-23 18:53:12'],
            [172,1,'Venda de tacacá','#ffd42a','2021-09-23 18:53:12','2021-10-19 18:15:43'],
            [173,2,'Agência bancária',null,'2021-10-18 18:29:41','2021-10-18 18:29:41'],
            [174,13,'Garagem de ônibus/microonibus',null,'2021-10-18 18:29:42','2021-10-18 18:29:42'],
            [175,2,'Estamparia',null,'2021-10-18 18:29:43','2021-10-18 18:29:43'],
            [176,1,'Venda de maniçoba',null,'2021-10-18 18:29:44','2021-10-18 18:29:44'],
            [177,1,'Venda e compra de ouro',null,'2021-10-18 18:29:45','2021-10-18 18:29:45'],
            [178,10,'Cooperativa de catadores',null,'2021-10-18 18:29:46','2021-10-18 18:29:46'],
            [179,2,'Aluguel de andaimes',null,'2021-10-18 18:29:47','2021-10-18 18:29:47'],
            [180,2,'Escritório (energia solar)',null,'2021-10-18 18:29:47','2021-10-18 18:29:47'],
            [181,2,'Agência de turismo',null,'2021-10-18 18:29:47','2021-10-18 18:29:47'],
            [182,2,'Lavanderia',null,'2021-10-18 18:29:47','2021-10-18 18:29:47'],
            [183,12,'Clínica (terapêutica)',null,'2021-10-18 18:29:47','2021-10-18 18:29:47'],
            [184,1,'Comércio e exportação de madeiras',null,'2022-01-18 19:21:58','2022-01-18 19:21:58'],
            [185,8,'Sindicato',null,'2022-01-18 19:21:58','2022-01-18 19:21:58'],
            [186,2,'Serviço de pintura (automotiva)',null,'2022-01-18 19:21:59','2022-01-18 19:21:59'],
        ];

        // Construa os dados no formato esperado
        $data = array_map(function ($row) use ($columns) {
            return array_combine($columns, $row);
        }, $values);

        // Insira os dados
        DB::table('subclasses')->insert($data);
    }
}
