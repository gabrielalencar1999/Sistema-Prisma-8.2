<?php 
	require_once('../../../api/config/config.inc.php');
	require FILE_BASE_API . '/vendor/autoload.php';
	
	use Functions\NFeService;
	use Database\MySQL;
	use NFePHP\NFe\Common\Standardize;
	use Functions\Atividade;

	$pdo = MySQL::acessabd();
	$base_sel = "bd_G000001";


/*

	$conteudo = '"raca_id","raca_idespecie","raca_nome"
	1,1,"Beagle"
	2,1,"Golden Retriever"
	3,2,"Levkoy Ucraniano"
	4,0,"Siberiano"
	5,1,"Galgo Afegão"
	6,1,"Akita Inu"
	7,1,"American Bully"
	8,1,"American Staffordshire terrier"
	9,1,"Basenji"
	12,1,"Basset Hound"
	13,1,"Bernese"
	14,1,"Bichon Frisé"
	15,1,"Bloodhound"
	16,1,"Boiadeiro Australiano"
	17,1,"Border Collie"
	18,1,"Borzoi"
	19,1,"Boston Terrier"
	20,1,"Boxer"
	21,1,"Buldogue Francês"
	22,1,"Buldogue Inglês"
	23,1,"Bull Terrier"
	24,1,"Cane Corso"
	25,1,"Cavalier King Charles Spaniel"
	26,1,"Chihuahua"
	27,1,"Chow Chow"
	28,1,"Cocker Spaniel Inglês"
	29,1,"Corgi"
	30,1,"Dachshund"
	31,1,"Dálmata"
	32,1,"Doberman"
	33,1,"Dogo Argentino"
	34,1,"Dogue Alemão"
	35,1,"Dogue de bordeaux"
	36,1,"Fila Brasileiro"
	37,1,"Fox Paulistinha"
	38,1,"Vira-Lata"
	39,1,"Galguinho Italiano"
	40,1,"Galgo Inglês"
	41,1,"Husky Siberiano"
	42,1,"Jack Russel Terrier"
	43,1,"Labradoodle"
	44,1,"Labrador Retriever"
	45,1,"Lhasa Apso"
	46,1,"LuLu da Pomerânia"
	47,1,"Malamute do alasca"
	48,1,"Maltês"
	49,1,"Mastiff Inglês"
	50,1,"Mastim Napolitano"
	51,1,"Mastim Tibetano"
	52,1,"Papillon"
	53,1,"Pastor Alemão"
	54,1,"Pastor Australiano"
	55,1,"Pastor Belga"
	56,1,"Pastor de Shetland"
	57,1,"Pastor do Cáucaso"
	58,1,"Pastor Maremano Abruzes"
	59,1,"Pastor Suiço"
	60,1,"Pequinês"
	61,1,"Pinscher"
	62,1,"Pit Bull"
	63,1,"Pointer Inglês"
	64,1,"Pit Monster"
	65,1,"Poodle"
	66,1,"Poodle Toy"
	67,1,"Pug"
	68,1,"Rottweiler"
	69,1,"Rough Collie"
	70,1,"Samoieda"
	71,1,"São Bernardo"
	72,1,"Schnauzer"
	73,1,"Setter Irlandês"
	74,1,"Shar-Pei"
	75,1,"Shiba"
	76,1,"Shih tzu"
	77,1,"Spitz Japonês"
	78,1,"Staffordshire Bull Terrier"
	79,1,"Terra Nova"
	80,1,"Weimaraner"
	81,1,"West Highland White Terrier"
	82,1,"Whippet"
	83,1,"Yorkshire Terrier"
	84,2,"Elfo"
	85,2,"Bambino"
	86,2,"Lobo"
	87,2,"Ragamuffin"
	88,2,"British Longhair"
	89,2,"Caracat"
	90,2,"Maine Coon"
	91,2,"Khao Manee"
	92,2,"Bobtail Americano"
	93,2,"Singapura"
	94,2,"Cymric"
	95,2,"Skookum"
	96,2,"Bobtail Japonês"
	97,2,"Toyger"
	98,2,"American Wirehair"
	99,2,"Burmilla"
	100,2,"Pixie-Bob"
	101,2,"American Curl"
	102,2,"LaPerm"
	103,2,"Tonquinês"
	104,2,"Javanês"
	105,2,"Somali"
	106,2,"Chausie"
	107,2,"Birmanês"
	108,2,"Sagrado da Birmânia"
	109,2,"Sokoke"
	110,2,"Devon Rex"
	111,2,"Turkish Van"
	112,2,"Korat"
	113,2,"Savannah"
	114,2,"Oriental Shorthair"
	115,2,"Chartreux"
	116,2,"Selkirk Rex"
	117,2,"Nebelung"
	118,2,"Cornish Rex"
	119,2,"Ocicat"
	120,2,"Selvagem"
	121,2,"Exótico de Pelo Curto"
	122,2,"Azul Russo"
	123,2,"Scottish Fold"
	124,2,"Snowshoe"
	125,2,"Manx"
	126,2,"Angorá Turco"
	127,2,"Bombaim"
	128,2,"Norueguês da Floresta"
	129,2,"Siberiano"
	130,2,"Bengal"
	131,2,"Siamês"
	132,2,"Ashera"
	133,2,"Munchkin"
	134,2,"Sphynx"
	135,2,"Mau Egípcio"
	136,2,"Himalaio"
	137,2,"Havana"
	138,2,"Europeu"
	139,2,"Balinês"
	140,2,"Mist Australiano"
	141,2,"Abissínio"
	142,2,"Persa"
	143,2,"Ragdoll"
	144,2,"British Shorthair"
	145,3,"Russo"
	146,3,"Roborovski"
	147,3,"Sírio"
	148,3,"Chinês"
	149,3,"Anão de Campbell"
	150,4,"Canário"
	151,4,"Calopsita"
	152,4,"Diamante de Gould"
	153,4,"Diamante Mandarim"
	154,4,"Manon"
	155,4,"Periquito"
	156,4,"Papagaio"
	157,4,"Cacatua"
	158,4,"Ganso "
	159,1,"SRD "
	160,2,"SRD"
	161,6,"Crioulo"
	162,6,"Quarto de Milha"
	163,6,"PSI"
	"
	';

	//EXPLODE AS LINHAS QUANDO PULAR LINHA
	$linha	=	explode("\n", $conteudo);
	
	for($i = 0; $i < sizeof($linha); $i++) {

		$var = trim($linha[$i]);	

		$linhas = explode(",", $var);
		

		$id_raca = $linhas[0];
		$id_especie = $linhas[1];
		$descricao_raca = str_replace('"','',$linhas[2]);


		$sql = "insert into $base_sel.raca (
			raca_id,
			raca_idespecie,
			raca_nome
		) values (
			'$id_raca',
			'$id_especie',
			'$descricao_raca'
		)";
		$stm = $pdo->prepare("$sql");	
        $stm->execute();
	}
		exit(); */


$conteudo = "dP1KOvqbHA,ALEXIA RAMOS DE ALMEIDA,,026.724.540-86,,BELA VISTA,,Panambi,(55) 99679-5504,4313904,RS,98280-000,RUA SANTA CRUZ,207,,26hx6NsKf1,Prontuário,web,2.0.0,,
VZKSkdBagL,Adriano da Silva matos,,019.609.280-90,,,,Panambi,(55) 99680-2510,4313904,RS,98280-000,,620,,26hx6NsKf1,Prontuário,web,2.0.0,,
soY6e1RBZD,Albrentino Mediros,,010.038.050-60,,,interior,Panambi,(55) 99932-7552,4313904,RS,98280-000, caxambu,,,26hx6NsKf1,Prontuário,web,2.0.0,,
2hHGPQdhrF,Alessandra Almeida,,,,Bela Vista,,Panambi,,,RS,,Rua Assis Brasil,172,,26hx6NsKf1,Prontuário,web,2.0.0,,
2ZRo9E1vxu,Alessandra Schierenbeck Soares,,,,Fritsch ,,Panambi,(55) 99174-1323,,RS,98280-000,Rua Otto Rehn ,216,,26hx6NsKf1,Prontuário,web,2.0.0,,
d16aDCKrCJ,Alexia Amanda pinheiro,,036.988.520-12,,fatima,,Panambi,(55) 98406-5433,4313904,RS,98280-000,rua bela vista ,71,,26hx6NsKf1,Prontuário,web,2.0.0,,
TRxUaqX0pg,Aline Quim,,945.136.860-49,,morro do gros,,Panambi,(55) 98424-2719,4313904,RS,98280-000,rua oscar strucker,689,,26hx6NsKf1,Prontuário,web,2.0.0,,
AUCJNHxEsf,Aline goulart,,938.127.950-00,,,,Santa Bárbara,(55) 99673-2729,,,,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
W9xBp4dJOk,Ana Claudia Fernandes Costabeber,,008.779.590-64,,Medianeira,,Panambi,(55) 99118-5158,,RS,,Rua Tiradentes,470,,26hx6NsKf1,Prontuário,web,2.0.0,,
zO9znKeFIC,Ana Flávia ,,,,,,Panambi,,4313904,RS,98280-000,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
HJyMJurBLz,Ana paula wendland de oliveira,,036.277.280-09,,centro,,Panambi,(55) 99117-4870,4313904,RS,98280-000,rua general osorio ,223,,26hx6NsKf1,Prontuário,web,2.0.0,,
8qbq1D1JiX,Angela maria weichsung hentges,,001.474.220-94,8053521806,erica,,Panambi,(55) 99688-8077,4313904,RS,98280-000,rua alberto pasqualini,296,,26hx6NsKf1,Prontuário,web,2.0.0,,
OqqpAJOG74,Antonio Mauricio de Lima,,037.068.990-94,,Zona Norte,,Panambi,(51) 99157-4168,,RS,,Julio Horst,195,,26hx6NsKf1,Prontuário,web,2.0.0,,
PTdb8WHBzs,Antonio Verbes de Oliveira,,629.821.170-53,,Arco Íris,casa,Panambi,(55) 99111-9432,,RS,98280-000,Rua Ernesto Dornelles ,459,,26hx6NsKf1,Prontuário,web,2.0.0,pai da tutora,
VmUsFoksGB,Araci Bacin ,,,,PLANALTO,,Panambi,(55) 99218-8484,4313904,RS,98280-000,RUA IVONE ,,,26hx6NsKf1,Prontuário,web,2.0.0,,
yHN6HDyuwK,Beatriz Pauline Goldschmidt,,938.855.800-63,,morro do gross,,Panambi,(55) 99231-4542,4313904,RS,98280-000,rua pinheiro machado,42,,26hx6NsKf1,Prontuário,web,2.0.0,,
fGtbQL4KyG,CARINE PETRY,,,,,,,(55) 99184-7884,,,,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
vMpx0IiXTv,Camila Machado,,010.673.960-39,,Trentini,Casa,Panambi,(55) 99223-6401,4313904,RS,98280-000,Rua Arnoldo Sholten,465,,26hx6NsKf1,Prontuário,web,2.0.0,,
PySPgoAYSl,Carol Heidecke,,,,Pavão,,Panambi,(55) 99186-2949,4313904,RS,98280-000,Casa,150,,26hx6NsKf1,Prontuário,web,2.0.0,,
IiFxMSrBWr,Daiane Cristina Fruhauf de Bairros,,010.700.400-37,,Zona Norte,,Panambi,(55) 99732-5583,,RS,,Rua Portugal ,437,,26hx6NsKf1,Prontuário,web,2.0.0,,
sf3Zs9gg64,Desirêe Lima do Rosario,,,,CENTRO ,GORILAS ,Panambi,(55) 8433-5455,4313904,RS,98280-000,Benjamin Constant, 132 Centro,132,,26hx6NsKf1,Prontuário,web,2.0.0,,
M9eMkAhHf0,Diciane Oliveira ,,012.460.740-36,,São Jorge ,,Panambi,,,RS,,Rua Konrad de Adenauer,1753,,26hx6NsKf1,Prontuário,web,2.0.0,,
CYU3MgTEc4,Dinara da Silva Rodrigues,,882.180.400-30,,Êrica,,Panambi,(55) 99125-9627,4313904,RS,98280-000,Prudêncio Cardoso,229,,26hx6NsKf1,Prontuário,web,2.0.0,,
3hgrxr2utp,Elaine Steiger,,619.883.240-68,,Italiana ,Casa ,Panambi,,,RS,,Rua Hermman Molz,210,,26hx6NsKf1,Prontuário,web,2.0.0,,
1gbWLTfdqG,Elsi Spode ,,561.794.480-00,,bela vista,,panambi,(55) 99114-1601,,RS,98280-000,rua viamão,55,,26hx6NsKf1,Prontuário,web,2.0.0,,
wIaF3VyMeS,FABIO ALEXANDRE DA ROLA OLIVEIRA,,024.223.730-46,,PAVÃO,LOTEAMENTO RICARDO SCHMIDT,PANAMBI,(55) 99122-6804,,RS,98280-000,RUA VILSON VINCENSI ,,,26hx6NsKf1,Prontuário,web,2.0.0,,
1sVs7BUY8W,Fabiana Dias,,836.253.800-72,,,Casa,Santa Bárbara do Sul,(55) 99901-7740,4316709,RS,98240-000,Rua Lauredano Lírio ,785,,26hx6NsKf1,DC,android,7.1.6,,
Lvppzo3WJ0,Fabiana da rosa oliveira,,020.191.010-17,,medianeira,,Panambi,(55) 99159-9922,4313904,RS,98280-000,rua carlos gomes ,90,,26hx6NsKf1,Prontuário,web,2.0.0,,
Yhh4aJH6ES,Fabiane Sinnemann,,037.365.110-43,,Bela Vista,,Panambi,(55) 99188-0864,,RS,98280-000,rua assis brasil,177,,26hx6NsKf1,Prontuário,web,2.0.0,,
I1ReQdqHv9,Flavia Pereira,,816.614.870-68,,Erica,,Panambi ,(55) 99734-7541,,RS,,Alagoas,267,,26hx6NsKf1,Prontuário,web,2.0.0,,
62hUyA10Da,GRACI FUNK,,,,,,Santa Bárbara do Sul,(55) 99137-7558,4316709,RS,98240-000,,,,26hx6NsKf1,Prontuário,web,2.0.0,TUTORA ENCAMINHADA DA FE,
u5J6GVHKzr,Gabriela zancanaro tonett,,,,morengaba,,Panambi,(55) 99626-3822,,RS,98280-000,linha morengaba ,,,26hx6NsKf1,Prontuário,web,2.0.0,,
Pz7vLYiA1V,Gilson cesar biberch,,,,,,Panambi,(51) 98142-1606,4313904,RS,98280-000,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
NUy5euVmEL,Gisela Dessbesell,,592.618.380-49,,Becker ,,Panambi,(55) 99166-0308,4313904,RS,98280-000,Rua dona Rosa Luiz,774,,26hx6NsKf1,Prontuário,web,2.0.0,,
1SkFBHBGAk,Griciane Ramos dos Santos Brizolla,,018.352.180-32,,Bela Vista,,Panambi,(55) 99617-9360,,RS,98280-000,Rua Frederico Prante,283,,26hx6NsKf1,Prontuário,web,2.0.0,,
LYzH9Gaj61,Guilerme Trein,,015.581.170-32,,Fátima,AP 601,Panambi,(55) 99134-2758,4313904,RS,98280-000,Rua Otto kepler,905,,26hx6NsKf1,Prontuário,web,2.0.0,,
NEuehbGxGs,Ilana Vanessa Berghmann,,855.113.150-87,,Piratini,,,(55) 98474-1071,,,,Rua Joseph Doeth,81,,26hx6NsKf1,Prontuário,web,2.0.0,,
CiRuKK1tsk,Iris winterfeld ramos,,362.015.290-04,6042151461,serrana,,Panambi,(55) 99171-1197,4313904,RS,98280-000,rua guapore ,89,,26hx6NsKf1,Prontuário,web,2.0.0,,
kwOX3gyA9i,Isadora neu ,,,,,,,,,,,,,,26hx6NsKf1,Prontuário,web,2.0.0,veio da cidade da rosana,
lvt44ZWeOg,Ivera ,,,,italiana,,Panambi,,4313904,RS,98280-000,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
JCz7PXp7qM,Jason Alex Dias Chisto,,035.127.740-40,,Armindo João Stalhofer,casa ,Panambi,,,RS,, Rua Aparício Guerreiro,55,,26hx6NsKf1,Prontuário,web,2.0.0,,
sxyXEUYiZB,Jean Rocha,,,,,,Panambi,(55) 99183-0899,4313904,RS,98280-000,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
96AANu183F,Jenifer Roffmann da Silva,,018.114.670-32,,bela vista,,,(55) 99132-6368,,,,Rua da republica,621,,26hx6NsKf1,Prontuário,web,2.0.0,,
NAKDbEB7ca,Jeniffer Barcelos Wolgien,,054.705.036-40,,Wogin,,,,,,,Rua Montevidéu,70,,26hx6NsKf1,Prontuário,web,2.0.0,,
stJRfCNnOF,Joceleia Farias dos Santos,,002.795.610-59,,Italiana,casa,Panambi,(55) 99152-7545,4313904,RS,98280-000,Rua Ajuricaba,263,,26hx6NsKf1,Prontuário,web,2.0.0,,
mZ4uZBkG51,Jordana Vicari,,025.001.640-04,,Morada do Sol,,Santa Bárbara ,,,RS,,João de Deus Vicente Lirio ,139,,26hx6NsKf1,Prontuário,web,2.0.0,,
A8uyJzYNa8,Jorge Carvalho,,005.179.900-67,,padroeira ,casa,Santa Bárbara do Sul,(55) 99964-8203,4316709,RS,98240-000,rua das palmas ,,,26hx6NsKf1,Prontuário,web,2.0.0,tutor encaminhado da fê,
TVd8x8hpwu,Josielme Adornes de Souza,,803.296.590-49,,Aparecida,Casa,Santa Bárbara ,,,RS,,Rua Serafim Ribas,72,,26hx6NsKf1,Prontuário,web,2.0.0,,
fLl6PVLZJf,Julio cezar araujo,,043.629.190-82,,laudelino ribas,,Panambi,(55) 99918-8437,4313904,RS,98280-000,rua joão armindo ,40,,26hx6NsKf1,Prontuário,web,2.0.0,,
CUljAtI2BF,Karina Pires da Silva,,013.458.840-10,,parque moinho velho,,panambi,(54) 99965-5272,,,98280-000,rua do recreio,93,,26hx6NsKf1,Prontuário,web,2.0.0,,
F9mgMUjEa8,LUCIANA LOPES,,020.356.720-07,,ERICA,,Panambi,(55) 99948-7841,4313904,RS,98280-000,RUA ERICA,195,,26hx6NsKf1,Prontuário,web,2.0.0,,
nUQ0Ok4Uje,LUCIENE KETTENHUBER,,,,,,Panambi,(55) 9158-8125,4313904,RS,98280-000,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
fbtbTYAIxN,Leonardo,,,,Italiana,Casa,Panambi,(55) 99986-6895,4313904,RS,98280-000,,105,,26hx6NsKf1,Prontuário,web,2.0.0,,
PL35DMx1vF,Lia (Quinze de Novembro),,,,,,,,,,,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
Sv2orDjMIy,MAJOLI WINTERFELD RAMOS SCHUTZ,,024.549.560-63,,PIRATINI,,Panambi,(55) 9171-1197,4313904,RS,98280-000,RUA BOM RETIRO ,,,26hx6NsKf1,Prontuário,web,2.0.0,,
Obwqu6C5S0,MARTA HELGUEIRA,,951.130.100-49,,Fritz,,Panambi,(55) 99209-0611,,RS,98280-000,Rua Florianopolis ,123,,26hx6NsKf1,Prontuário,web,2.0.0,,
xC4cnTvpmk,Mara Denise Dias,,958.833.120-04,,piratini,casa,Panambi,(55) 99601-3053,4313904,RS,98280-000,RUA JOSEPH DOETH,493,,26hx6NsKf1,Prontuário,web,2.0.0,,
xWWHpNvOv6,Marcelo Martins,,000.348.150-66,,Moinho Velho,,Panambi,(55) 99169-3987,,RS,,Gustavo Kullman,450,,26hx6NsKf1,Prontuário,web,2.0.0,,
j4VQfmYzXx,Marcos de Oliveira,,706.609.050-00,,serrana,,Panambi,(55) 99646-3244,4313904,RS,98280-000,rua manoel bernardino alves,235,,26hx6NsKf1,Prontuário,web,2.0.0,,
PJYAiR3AO0,Maria Eduarda Busnelo Machado Rodrigues,,016.819.160-10,,Centro,,Panambi,(55) 99711-7083,4313904,RS,98280-000,Rua Andrade Neves ,612,,26hx6NsKf1,Prontuário,web,2.0.0,,
KILUK7ytKs,Marisa Consencição Bottega pertile,,,,loeblien,,Santa Bárbara do Sul,(55) 9734-6187,4316709,RS,98240-000,rua flavio bortet ,67,,26hx6NsKf1,Prontuário,web,2.0.0,,
61b5cskvT6,Marluce Holdefer,,018.304.720-62,,Loeblen,,Santa Bárbara,,,,,Rua Tuiuti,149,,26hx6NsKf1,Prontuário,web,2.0.0,,
C9yXInfIBl,Michele daiane fogaça de miranda,,006.757.000-35,,zona norte ,,Panambi,(55) 99968-9128,4313904,RS,98280-000,rua deofino de abreu ,100,,26hx6NsKf1,Prontuário,web,2.0.0,,
eOecViwUtC,Michelle Schwarz,,957.403.090-34,,Italiana,,Panambi,(55) 99123-0550,4313904,RS,98280-000,Rua Santa Cruz ,40,,26hx6NsKf1,Prontuário,web,2.0.0,,
NgbPFlQcRb,Midiam ,,022.742.660-69,,,Zona Norte,Panambi,(55) 99672-9869,,RS,,Ervino krambert,30,,26hx6NsKf1,Prontuário,web,2.0.0,,
jcMrzDExCw,Milady de Oliveira Gonsalves ,,314.652.930-00,,parque moinho velho ,,Panambi,(55) 99101-9935,4313904,RS,98280-000,Rua do recreio ,,,26hx6NsKf1,Prontuário,web,2.0.0,,
QIu9AGG5Sw,Neuza Anahi Custódio ,,550.015.130-49,,alvorada,,panambi,(55) 99196-1604,,RS,98280-000,rua felício onofre sigas ,63,,26hx6NsKf1,Prontuário,web,2.0.0,,
LDUhIXGlaF,PREFEITURA DE PANAMBI,,,,,,Panambi,(55) 99642-6125,4313904,RS,98280-000,,,,26hx6NsKf1,Prontuário,web,2.0.0,ANA FLAVIA RESPONSAVEL,
rhCFioeQGE,Patrícia de Fátima Cordeiro de Oliveira,,,,São Jorge,,Panambi,(55) 99118-9291,4313904,RS,98280-000,Rua Morengaba,450,,26hx6NsKf1,Prontuário,web,2.0.0,,
BwpLOIlW9P,Paulo Ricardo,,024.951.690-07,,Loeblein,,Santa Bárbara do Sul,(55) 99618-1482,4316709,RS,98240-000,Rua Tuiuti ,359,,26hx6NsKf1,Prontuário,web,2.0.0,,
vXk04nVBKz,Raquel Soares,,024.107.190-90,,Cerutti,,Santa Barbara do Sul,(55) 99963-4460,,RS,,Rosauro Costa ,500,,26hx6NsKf1,Prontuário,web,2.0.0,,
e3bplyl5BA,Regiane kopp jardim,,002.055.460-56,,bela vista ,,Panambi,(55) 99968-9919,4313904,RS,98280-000,rua jose bonifacio,230,,26hx6NsKf1,Prontuário,web,2.0.0,,
r7K32DPWJL,Roberta Eleonora Pinheiro,,000.770.990-01,,Italiana,,Panambi,(55) 99122-0186,4313904,RS,98280-000,Rua Ajuricaba ,480,,26hx6NsKf1,Prontuário,web,2.0.0,,
6QlnWY4wPI,Rolita Pucci Raichle,,592.620.520-49,,Centro,,Panambi,(55) 99969-4567,4313904,RS,98280-000,Rua Andrade Neves,172,,26hx6NsKf1,Prontuário,web,2.0.0,,
KIirXKVVkD,Romiu antunes da silva,,232.961.600-78,,fatima,,Santa Bárbara do Sul,(55) 9647-4781,4316709,RS,98240-000,rua 20 de setembro,99,,26hx6NsKf1,Prontuário,web,2.0.0,,
gNhcM5R18O,SILVIA GERMANO,,,,ZONA NORTE,CASA,Panambi,(55) 99207-1079,4313904,RS,98280-000,RUA MUNIQUE,355,,26hx6NsKf1,Prontuário,web,2.0.0,,
bFOxq7ZbPi,Shirlei Quevedo dos Santos,shirleiqs@hotmail.com,817.537.990-15,,São Jorge,Casa,Panambi,(55) 99131-2348,4313904,RS,98280-000,Rua Carlos Franke,40,,26hx6NsKf1,Prontuário,web,2.0.0,separar os cães pois brigam entre eles,
fJPKgf9E21,Sibele Raquel Desebesell,,026.108.590-58,,Morro do Grosse,,Panambi,,,RS,,Passagem Adam Kloss,54,,26hx6NsKf1,Prontuário,web,2.0.0,,
jXCfpm6rln,Sueli Amaral Donelles,,049.054.520-31,,Parque Moinho Velho,,Panambi,(55) 99108-2251,,RS,,Rua do Recrio,238,,26hx6NsKf1,Prontuário,web,2.0.0,,
ITTb9IesM4,Susiani Someer,,950.582.220-00,,Italiana,,Panambi,(54) 99155-8123,4313904,RS,98280-000,rua andrade neves,744,,26hx6NsKf1,Prontuário,web,2.0.0,,
qdY8PVjnEw,THAISE ,,,,,,Panambi,(55) 99970-7978,4313904,RS,98280-000,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
yrzlxDPCPO,Telma de Souza Pires,,046.467.810-26,,,ap,santa barbara,(55) 9189-5982,,RS,,Av. Cel. Victor Dumoncel 1328 ap. 02 Santa Bárbara do Sul RS,1328,,26hx6NsKf1,Prontuário,web,2.0.0,,
11KiOYAFHG,Thainá Fernandes Batista,,030.650.210-05,,Fátima,,Panambi,(55) 9997-1122,,RS,98280-000,Rua Sertorio,410,,26hx6NsKf1,Prontuário,web,2.0.0,trabalha na claro,
z0WywITvJR,VALERIA LOPES,,044.351.460-70,,INTERIOR,,Panambi,(55) 99176-9006,4313904,RS,98280-000,LINHA MORENGABA,INTERIOR,,26hx6NsKf1,Prontuário,web,2.0.0,,
DRGmz6PIJ0,VANESSA GONÇALVES NUNES GELATTI,,008.261.870-48,,ERICA,,Panambi,,4313904,RS,98280-000,RUA AUGUSTO LIBERTNER,335,,26hx6NsKf1,Prontuário,web,2.0.0,,
H9sC3xClw1,Vanessa ,,,,,,,(55) 99138-7099,,,,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
ciW6JMGUAQ,Vanessa da silva bornhold,,034.776.910-16,,centro,,Panambi,(55) 99170-7362,4313904,RS,98280-000,rua barão do rio branco,266,,26hx6NsKf1,Prontuário,web,2.0.0,,
7AIGEDAiUj,Vani Carla Breitenbach,,004.367.180-22,,jaciandi,,Panambi,(55) 99146-2119,4313904,RS,98280-000,rua lagoa vermelha ,265,,26hx6NsKf1,Prontuário,web,2.0.0,,
adsPxNphcf,Viviane Klein Horbach,,040.847.510-22,,Centro,casa ,Quinze de Novembro,,,RS,,Rua Julio Maurer,858,,26hx6NsKf1,Prontuário,web,2.0.0,,
UB7uMcfYDx,adriano bore ,,,,zona norte,,panambi,,,RS,,rua max rattan,59,,26hx6NsKf1,Prontuário,web,2.0.0,,
FjXFswIwZD,adão ,,,,,casa,santa barbara ,(55) 99694-7356,,RS,98280-000,rua otto,148,,26hx6NsKf1,Prontuário,web,2.0.0,,
pk7RjqVESU,alesandre alves,,961.141.340-87,,zona norte,casa,panambi,(55) 99231-5793,,RS,98280-000,rua do rincão ,408,,26hx6NsKf1,Prontuário,web,2.0.0,,
oONNXxqCud,alessandra silva padilha,,012.527.670-21,,são jorge,,Panambi,(55) 99673-0433,4313904,RS,98280-000,Rua Vili Dietrich,940,,26hx6NsKf1,Prontuário,web,2.0.0,,
Qlf1UuDrG1,ana paula vargas da silva,,021.583.270-14,,zona norte,,Panambi,(55) 99158-9665,4313904,RS,98280-000,rua nicaragua ,190,,26hx6NsKf1,Prontuário,web,2.0.0,,
pm5OqxRsZ1,anderson Lara,,,,,,,(55) 99985-8909,,,,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
DNIE4dTxMJ,andre hubensause,,,,,,,,,,,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
OzfGCx8p66,anita de arruda schutz,,966.470.190-49,,italiana,,,(55) 99136-4480,,,98280-000,rua passo do fiusa,558,,26hx6NsKf1,Prontuário,web,2.0.0,,
pMk7bv5mRm,carlos britz,,,,,,,(55) 98414-4810,,,,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
4BehomrQaw,carlos eduardo ambrozio gularte,,029.373.820-37,,bela vista,casa,panambi,(55) 99639-3083,,RS,98280-000,rua augusto bosse,176,,26hx6NsKf1,Prontuário,web,2.0.0,,
jFJFg2epjA,celi isolde ball,,633.230.010-87,,vila nova,,panambi,,,RS,98280-000,rua jacob bock,276,,26hx6NsKf1,Prontuário,web,2.0.0,proximo ao weidle,
9jx63DJvDm,cristiane rifisch laps,,022.955.230-79,,italiana,,Panambi,(55) 99153-8779,4313904,RS,98280-000,rua madri,121,,26hx6NsKf1,Prontuário,web,2.0.0,,
Tbd6GevfI6,daniela kersting mesadri janke,,682.907.480-00,,parque moinho velho,,panambi,(55) 99103-1651,,RS,98280-000,rua do recreio ,205,,26hx6NsKf1,Prontuário,web,2.0.0,,
yDDmkVkKMs,darlan greff,,022.898.940-01,,são jorge,,Panambi,(55) 99158-9429,4313904,RS,98280-000,rua morengaba ,890,,26hx6NsKf1,Prontuário,web,2.0.0,,
gaNjT3MCKH,darlane nyland,,027.266.990-30,,,,,(51) 99657-8901,,,,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
ZyeQt7WT3w,denise dos santos,,310.052.608-26,,zona norte,,Panambi,(51) 99972-2067,4313904,RS,98280-000,rua monique,499,,26hx6NsKf1,Prontuário,web,2.0.0,,
vuBn5bkktO,douglas moura de araujo,,032.909.590-07,,serrana,,Panambi,,4313904,RS,98280-000,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
Lt2kncQK09,edilson brandt da rosa,,383.342.830-91,,piratini,,Panambi,(55) 99630-8524,4313904,RS,98280-000,rua lucindo ramos,95,,26hx6NsKf1,Prontuário,web,2.0.0,,
YBPNqaQZLx,elisandra ferigolo,,004.563.740-78,,,,Panambi,(55) 99159-7166,4313904,RS,98280-000,rua otto kepler ,216,,26hx6NsKf1,Prontuário,web,2.0.0,,
HhXXKdGDKT,eloise (prefeitura),,,,,,,,,,,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
wWahtG2KH0,everton romario dos santos,,024.150.440-67,,italiana ,,Panambi,,4313904,RS,98280-000,rua ajuricaba,,,26hx6NsKf1,Prontuário,web,2.0.0,,
hn9C2qloZX,fabio coelho simoes ,,932.883.360-49,,italiana,,Panambi,(53) 99136-6848,4313904,RS,98280-000,rua Josephe George Lembert,128,,26hx6NsKf1,Prontuário,web,2.0.0,,
gP7nIxsogY,fernanda britzke ensenbach,,037.128.910-67,,,,panambi,(55) 99175-1206,,RS,98280-000,rua linha iria piria 1,,,26hx6NsKf1,Prontuário,web,2.0.0,,
ZaxT4Pl0xi,francielle sanfelice,,,,zona norte,casa,Panambi,(55) 99644-2899,4313904,RS,98280-000,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
cASnhpklgi,gilberto correa de barros,,001.757.990-24,,santa maria,,Panambi,(55) 99963-0085,4313904,RS,98280-000,avenida victorino da cass,600,,26hx6NsKf1,Prontuário,web,2.0.0,,
swvBMDdx32,gustavo ramo dos santos,,040.687.710-65,,,,Panambi ,(55) 99127-7950,,RS,98280-000,rua nicaragua,170,,26hx6NsKf1,Prontuário,web,2.0.0,,
7Bp4U06bsY,henri jorge markus junior,junior906markus@gmail.com,037.867.270-31,,piratini,casa,Panambi,(55) 99115-1218,4313904,RS,98280-000,rua 15 de novembro,446,,26hx6NsKf1,Prontuário,web,2.0.0,,
sPythpAD2n,ilgon kist,,221.450.100-25,,,,Panambi,,4313904,RS,98280-000,rua prudencio cardona ,97,,26hx6NsKf1,Prontuário,web,2.0.0,,
NG127ddTcD,iria hoffmann,,505.632.620-87,3015523263,bela vista,,Panambi,(55) 99127-0471,4313904,RS,98280-000,rua santa cruz,331,,26hx6NsKf1,Prontuário,web,2.0.0,,
tR579amjDt,jocenara rodrigues,,,,italiana,,panambi,(55) 99146-6728,,RS,98280-000,rua hermann molz,378,,26hx6NsKf1,Prontuário,web,2.0.0,,
ukumCl1anb,jonas portes,,001.764.360-06,,loeblen,,Santa Bárbara do Sul,(55) 99676-3822,4316709,RS,98240-000,rua tuiuti,366,,26hx6NsKf1,Prontuário,web,2.0.0,,
l1R7EgO8uM,joner lamb,,949.448.300-68,,italiana,casa,Panambi,(55) 99148-4286,4313904,RS,98280-000,rua simimbu ,46,,26hx6NsKf1,Prontuário,web,2.0.0,,
71iq7niBQc,joão antonio ribeiro meireles,,756.149.101-82,,piratini,,Panambi,(55) 99725-3917,4313904,RS,98280-000,avenida presidente kenidi,145,,26hx6NsKf1,Prontuário,web,2.0.0,,
7sP4vX2rcG,juliana otoneli,,011.189.880-35,01092098291,erica,casa,Panambi,(55) 98411-4567,4313904,RS,98280-000,rua henri,,,26hx6NsKf1,Prontuário,web,2.0.0,,
Tl53AFT8bd,karina pires da silva,,,,italiana,,panambi,(54) 99965-5272,,RS,98280-000,rua passo do fiuza ,93,,26hx6NsKf1,Prontuário,web,2.0.0,,
MFeTs5TnPZ,kelen cristina siqueira bornholdt,,946.771.900-20,,Medianeira ,,Panambi,(55) 99972-0287,4313904,RS,98280-000,rua Getúlio Vargas ,949,,26hx6NsKf1,Prontuário,web,2.0.0,,
X2tBw3rYEF,leonardo franzmann,,037.323.080-06,,,ap 103,Panambi,(55) 99126-8457,4313904,RS,98280-000,rua gaspar martins,370,,26hx6NsKf1,Prontuário,web,2.0.0,,
aEp3OtCd4H,liria teresinha donaut przyczynski,,390.379.400-72,,,,Panambi,(55) 99914-4105,4313904,RS,98280-000,passo do fiuza ,27,,26hx6NsKf1,Prontuário,web,2.0.0,,
ETwFMinJi0,luciana de castro regis,,931.617.000-15,,fatima,,Panambi,(55) 99169-5418,4313904,RS,98280-000,otto kepler,370,,26hx6NsKf1,Prontuário,web,2.0.0,,
a4sNlRDIvW,luciane rodrigues,,001.282.980-30,,Fensterseifer,,Panambi,(55) 99131-4111,4313904,RS,98280-000,rua anita garibalde,95,,26hx6NsKf1,Prontuário,web,2.0.0,,
vMAiywCrPc,marcia da silva,,944.550.400-30,,CENTRO,CASA,PANAMBI,,,RS,98280-000,RUA DO RECREIO ,100,,26hx6NsKf1,Prontuário,web,2.0.0,,
nUofnkfsVG,mariane da silva,,000.237.900-75,,medianeira,,panambi,(55) 99150-1983,,RS,98280-000,rua timbara ,1155,,26hx6NsKf1,Prontuário,web,2.0.0,,
GiI8HUwVpq,marileia sirlei bester,,998.273.440-72,,becker,casa,panambi,(55) 9168-3224,,RS,98280-000,dona rosa luiza ,801,,26hx6NsKf1,Prontuário,web,2.0.0,,
z6QMs7FSD6,maristela lopes ,,958.865.320-72,,piratini,,panambi,(54) 99704-1546,,RS,98280-000,rua piratini,235,,26hx6NsKf1,Prontuário,web,2.0.0,,
mezzR960JP,murijan de souza barbosa,,892.956.330-91,,fatima,,panambi,(55) 99191-3325,,RS,98280-000,rua rinaldina almeida,279,,26hx6NsKf1,Prontuário,web,2.0.0,,
4hILaBUI6Y,natalia oliveira da rosa,,024.649.540-55,,bela vista,,Panambi,(55) 99119-0589,4313904,RS,98280-000,rua augusto bosse,233,,26hx6NsKf1,Prontuário,web,2.0.0,,
C6zgYuk3lI,oneide justina finado,,216.849.600-53,,,,Santa Bárbara do Sul,(55) 99602-1960,4316709,RS,98240-000,rua luciano gulino,384,,26hx6NsKf1,Prontuário,web,2.0.0,,
MChKTgJm1y,pablo gonsalves,,989.757.650-91,,medianeira,,,(55) 99173-4038,,,98280-000,rua augusto loose ,215,,26hx6NsKf1,Prontuário,web,2.0.0,,
U2fEbxYkoI,pamela costorio,,026.697.390-67,,becker,,Panambi,(55) 99212-6698,4313904,RS,98280-000,rua Antônio hitler ,130,,26hx6NsKf1,Prontuário,web,2.0.0,,
vSwDSG36bk,rafael anastacio penafor,,034.549.980-85,,310,ap 101,Panambi,(55) 99917-1319,4313904,RS,98280-000,rua porto alegre ,310,,26hx6NsKf1,Prontuário,web,2.0.0,,
CtISDyCYbe,rita de cassia de medeiros soares,,001.328.390-16,,planalto,,,(55) 98407-4678,,,98280-000,rua godfrit reuth ,279,,26hx6NsKf1,Prontuário,web,2.0.0,,
L2Br3xOf72,ritieli feltrin,,027.628.290-62,,trentini,,Panambi,(55) 99921-0429,4313904,RS,98280-000,rua nepal,240,,26hx6NsKf1,Prontuário,web,2.0.0,,
1PXEDU2wcH,ronilsa winterfeld ,,003.723.180-45,,bela vista ,,Panambi,(55) 99985-8273,4313904,RS,98280-000,rua erechim,110,,26hx6NsKf1,Prontuário,web,2.0.0,,
02QX4qfEpF,rosa ventrusculo ,,669.192.660-91,,,,,(55) 99988-6499,,,,,,,26hx6NsKf1,Prontuário,web,2.0.0,,
Pr2lOYYlh8,rosana rycerz,,,,,,,,,,,santa barbara ,,,26hx6NsKf1,Prontuário,web,2.0.0,,
47YlUikeHn,rubia pucci,,666.410.470-91,,,,Santa Bárbara do Sul,(55) 99100-4899,4316709,RS,98240-000,rua jose neto,151,,26hx6NsKf1,Prontuário,web,2.0.0,,
Ip4LJWWbDm,taline borré farsen,,039.429.380-06,,Centro,ap 402,panambi,(55) 99119-6183,,RS,98280-000,rua andrade neves,410,,26hx6NsKf1,Prontuário,web,2.0.0,,
LfdOx3Tshr,zuleika Aparecida dos Santos,,888.020.350-91,,são jorge,casa,Panambi,(55) 9102-5300,4313904,RS,98280-000,panambi,466,,26hx6NsKf1,Prontuário,web,2.0.0,,
";
	//EXPLODE AS LINHAS QUANDO PULAR LINHA
	$linha	=	explode("\n", $conteudo);
	for($i = 0; $i < sizeof($linha); $i++) {

		$var = trim($linha[$i]);	
		$linhas = explode(",", $var);
		

		//id
		$id_cripto = $linhas[0];

		//consumidor
		$nome_cliente = $linhas[1];
		$email = $linhas[2];
		$cpfcnpj = $linhas[3];
		$rg = $linhas[4];
		$bairro = $linhas[5];
		$complemento = $linhas[6];
		$cidade = $linhas[7];
		$Telefone1 = $linhas[8];
		$cod_cidade = $linhas[9];
		$estado = $linhas[10];
		$cep = $linhas[11];
		$endereco = $linhas[12];
		$numero_casa = $linhas[13];
		$obs = $linhas[19];


		$sql="insert into $base_sel.consumidor (Cod_Regiao,Nome_Consumidor,CIDADE,BAIRRO,Nome_Rua,COMPLEMENTO,CGC_CPF,Num_Rua,rg,obs_pedido,loja1,Data_Cadastro) values (
			'$cod_cidade',
			'$nome_cliente',
			'$cidade',
			'$bairro',
			'$endereco',
			'$complemento',
			'$cpfcnpj',
			'$numero_casa',
			'$rg',
			'$obs',
			'$id_cripto',
			NOW()
		)";
		$stm = $pdo->prepare($sql);	
		$stm->execute();



		$sql="select * from $base_sel.consumidor where loja1 = '$id_cripto'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		while($rst = $stm->fetch(PDO::FETCH_OBJ)){
			$id_consumidor = $rst->CODIGO_CONSUMIDOR;
		}

		if($Telefone1 != ""){

			$Telefone1 = str_replace("(", "",$Telefone1); 
			$Telefone1 = str_replace(")", "",$Telefone1); 
			$Telefone1 = str_replace("-", "",$Telefone1); 
			$DDD_CELULAR = substr($Telefone1,0,2);			
			$FONE_CELULAR = substr(trim($Telefone1),2,10);

			//verifica se existe contato igual para o consumidor		
			$sql="select * from $base_sel.telemail where fone_telefone = '$Telefone1'";
			$stm = $pdo->prepare("$sql");	
			$stm->execute();
			if($stm->rowCount() == 0){
				//inclui novo contato-------------------------------------
				$sql2="insert into $base_sel.telemail (
					fone_ddd,
					fone_telefone,
					fone_tipo,
					fone_idcliente
				) values (
					'$DDD_CELULAR',
					'$FONE_CELULAR',
					'1',
					'$id_consumidor'
				)";
				$stm2 = $pdo->prepare("$sql2");	
				$stm2->execute();
				//------------------------------------------------------------//
			}
		}
		if($email != ""){

			$email_cliente = $email;

			//verifica se existe contato igual para o consumidor		
			$sql="select * from $base_sel.telemail where fone_telefone = '$Telefone1'";
			$stm = $pdo->prepare("$sql");	
			$stm->execute();
			if($stm->rowCount() == 0){
				//inclui novo contato-------------------------------------
				$sql2="insert into $base_sel.telemail (
					fone_telefone,
					fone_tipo,
					fone_idcliente
				) values (
					'$email_cliente',
					'3',
					'$id_consumidor'
				)";
				$stm2 = $pdo->prepare("$sql2");	
				$stm2->execute();
				//------------------------------------------------------------//
			}
		}
	}

	//==================================================================================================================================================================================
	//==================================================================================================================================================================================
	//==================================================================================================================================================================================
	//==================================================================================================================================================================================

$conteudo = "Y0K0HRDkx0,2021-04-19 14:23:41,02QX4qfEpF,galgo,Canino,Galgo afegão,Macho,,preta,docil,,0,1056980,26hx6NsKf1,Prontuário,web,2.0.0,,,
FqJIPo321p,2021-11-10 03:00:00,11KiOYAFHG,Marri,Felino,SRD,Fêmea,,Cinza clara,Dócil,0,0,952891,26hx6NsKf1,Prontuário,web,2.0.0,2 doses do vermífugo - só tem ela,https://vetsmart-parsefiles.s3.amazonaws.com/60b8583c85221be9d9740593fe38d76e_image.png,
BzGFqV9LUz,2013-02-11 11:46:35,1PXEDU2wcH,preto,Canino,SRD,Macho,,,,0,0,959710,26hx6NsKf1,Prontuário,web,2.0.0,,,
3r1HnAafhP,2022-02-04 03:00:00,1SkFBHBGAk,Bebezinho,Canino,Shih tzu,Macho,,marrom,Dócil,0,0,990893,26hx6NsKf1,Prontuário,web,2.0.0,,,
ISqpzBdxsO,2011-05-12 14:33:17,1gbWLTfdqG,Peter,Canino,SRD,Macho,,Bicolores,Dócil,0,0,1091864,26hx6NsKf1,Prontuário,web,2.0.0,,,
2QYadL5tN4,2009-04-17 03:00:00,1sVs7BUY8W,Bonito,Canino,Chow Chow,Macho,,,,,,1054398,26hx6NsKf1,DC,android,7.1.6,,,
kQxk25ABVO,2021-12-05 14:20:09,2ZRo9E1vxu,Arya,Felino,SRD,Fêmea,,Tigradoa (Brindle),Alerta,0,0,991839,26hx6NsKf1,Prontuário,web,2.0.0,,,
YD7eMPvPyf,2018-04-23 21:33:11,2hHGPQdhrF,Malévola,Canino,Pug,Fêmea,,Preta,Dócil,0,0,1063630,26hx6NsKf1,Prontuário,web,2.0.0,,,
UdhfQGIYx1,2018-05-23 16:56:59,3hgrxr2utp,Mel,Canino,Shih tzu,Fêmea,,branca e marrom,Agressivo,1,0,1107524,26hx6NsKf1,Prontuário,web,2.0.0,,,
Rfs5BXIkCL,2020-02-17 17:10:02,47YlUikeHn,aisha,Canino,SRD,Fêmea,,marrom,Assustado,0,0,969269,26hx6NsKf1,Prontuário,web,2.0.0,,,
aA5ya6CN5g,,4BehomrQaw,bela,Canino,Shih tzu,Fêmea,,tricolor,Dócil,0,0,1076052,26hx6NsKf1,Prontuário,web,2.0.0,,,
jQoovGYRJs,2021-02-02 17:24:33,4BehomrQaw,theo,Canino,Shih tzu,Macho,,Bicolores,Agressivo,0,0,1076000,26hx6NsKf1,Prontuário,web,2.0.0,,,
zHP2MmbEQb,2018-03-10 16:46:06,4hILaBUI6Y,Bolota,Felino,SRD,Macho,,,,1,0,999395,26hx6NsKf1,Prontuário,web,2.0.0,,,
kAMfakt4gO,2022-03-22 21:24:09,61b5cskvT6,Persa filhote ,Felino,Persa,Macho,,,,,1,1106632,26hx6NsKf1,Prontuário,web,2.0.0,,,
IeEfSs4wC2,2012-01-26 16:55:02,62hUyA10Da,MAYA,Felino,SRD,Fêmea,,Bicolores,Agressivo,1,0,935516,26hx6NsKf1,Prontuário,web,2.0.0,,,
bmL7WC10yS,2016-12-30 02:00:00,6QlnWY4wPI,Casthel,Canino,SRD,Macho,,Bronze,Agressivo,0,0,924911,26hx6NsKf1,Prontuário,web,2.0.0,,https://vetsmart-parsefiles.s3.amazonaws.com/a2442bffc361f954838baf13c037b17d_image.png,
izvZYdLUmk,2021-11-12 03:00:00,71iq7niBQc,cheise,Canino,Chow Chow,Macho,,marrom,Alerta,0,0,961555,26hx6NsKf1,Prontuário,web,2.0.0,,,
sFQbrF9BXY,2022-01-16 18:23:49,7AIGEDAiUj,Berenice,Felino,SRD,Fêmea,,Siamês,Agressivo,0,0,1008716,26hx6NsKf1,Prontuário,web,2.0.0,,,
UpCdejGu2C,2014-11-16 02:00:00,7Bp4U06bsY,Valentin da Boa Vista,Equino,Crioulo,Macho,,Preta, Fulva e Branco,Dócil,0,0,922021,26hx6NsKf1,Prontuário,web,2.0.0,,https://vetsmart-parsefiles.s3.amazonaws.com/7030e4c20d18b702f645e182a1bb3757_image.png,
28TFUmDwG3,2018-04-15 14:59:53,7sP4vX2rcG,nina,Felino,SRD,Fêmea,,brancas com cinza,brava ,1,0,1052862,26hx6NsKf1,Prontuário,web,2.0.0,paciente foi castrada dia 140422 retirou os pontos durante a noite ,,
2g2yJDuHUI,2021-11-10 03:00:00,8qbq1D1JiX,Mabel,Felino,SRD,Fêmea,,tricolor,Dócil,0,0,1115401,26hx6NsKf1,Prontuário,web,2.0.0,,,
wCgUXU3i9W,2021-11-10 03:00:00,8qbq1D1JiX,Simba,Felino,SRD,Macho,,Branca,Dócil,0,0,1115398,26hx6NsKf1,Prontuário,web,2.0.0,,,
cPyk2nLzEh,2020-07-24 14:28:45,96AANu183F,Afonso,Roedor,Porquinho da Índia,Macho,,,Dócil,0,0,931572,26hx6NsKf1,Prontuário,web,2.0.0,,,
DCDmwnYdwG,2018-08-07 11:42:00,9jx63DJvDm,Sky,Canino,Shih tzu,Fêmea,,Bicolores,Dócil,0,0,993046,26hx6NsKf1,Prontuário,web,2.0.0,,,
wGV56JvsJe,2013-01-17 02:00:00,A8uyJzYNa8,Taz,Canino,Pastor Alemão,Macho,,Bicolores,Alerta,0,1,923906,26hx6NsKf1,Prontuário,web,2.0.0,problemas cardíacos e cirurgia de rompimento do ligamento cruzado,,
Lc3sNt6fMp,2018-05-31 19:36:08,AUCJNHxEsf,Chico,Felino,SRD,Macho,,Bicolores,Dócil,,0,1119825,26hx6NsKf1,Prontuário,web,2.0.0,,,
mKb2I8cFnP,2014-01-19 02:00:00,BwpLOIlW9P,Diana,cão,Pit Bull,Fêmea,,,Dócil,0,0,925735,26hx6NsKf1,Prontuário,web,2.0.0,diminuiu a comida- desde ontem notou que estava apática- hoje de meio dia notou que está com sangue na urina e pus na vagina- ja teve filhote 2x e faz 3meses que entrou em cio. vacinas em dia e vermifugo em dia a cada 3 meses.,https://vetsmart-parsefiles.s3.amazonaws.com/ab15878663a1eade39cb22daa4da6f25_image.png,
f7toHwQrYr,2011-04-04 03:00:00,C6zgYuk3lI,Aurora,Canino,SRD,Fêmea,,Bicolores,Alerta,0,0,1116959,26hx6NsKf1,Prontuário,web,2.0.0,,,
JDuz7BeUvz,2021-12-29 13:22:08,C9yXInfIBl,gatinha,Felino,SRD,Fêmea,,siames ,Dócil,0,0,1026419,26hx6NsKf1,Prontuário,web,2.0.0,,,
0sITJ98i8N,2021-08-24 18:24:10,CUljAtI2BF,Teddy,Canino,SRD,Macho,,Bicolores,Hiperativo,1,0,1020606,26hx6NsKf1,Prontuário,web,2.0.0,,,
Oreiv8BH9Y,,CYU3MgTEc4,Joventino,Canino,SRD,Macho,,Branca,Agressivo,1,0,1114505,26hx6NsKf1,Prontuário,web,2.0.0,,,
w2Pil4oJ5A,2014-02-16 03:00:00,CiRuKK1tsk,Monalisa,Canino,SRD,Fêmea,,preta e caramelo,Dócil,1,1,967078,26hx6NsKf1,Prontuário,web,2.0.0,,,
DcBaiQC1FS,2020-07-29 14:32:20,CtISDyCYbe,shelbi,Canino,Shih tzu,Macho,,Bicolores,Dócil,0,0,940145,26hx6NsKf1,Prontuário,web,2.0.0,,,
bVi003GS8X,2016-03-29 14:00:13,DNIE4dTxMJ,fiona,Canino,SRD,Fêmea,,Bicolores,Estressado,0,0,1026518,26hx6NsKf1,Prontuário,web,2.0.0,,,
YrBv1TmEnY,2019-07-31 17:31:45,DRGmz6PIJ0,SPIKE,Canino,Shih tzu,Macho,,Bicolores,Dócil,0,0,941938,26hx6NsKf1,Prontuário,web,2.0.0,,,
Mz1etzFJBu,2019-05-03 03:00:00,ETwFMinJi0,mouro,Felino,SRD,Macho,,,,1,0,1076957,26hx6NsKf1,Prontuário,web,2.0.0,,,
FH62WLrvh7,2018-05-31 18:55:08,F9mgMUjEa8,KIRA,Felino,SRD,Fêmea,,Tigradoa (Brindle),Agressivo,1,0,1119698,26hx6NsKf1,Prontuário,web,2.0.0,,,
smg0OM0fef,2017-04-17 21:09:28,FjXFswIwZD,hercules ,Canino,SRD,Macho,,marron ,Assustado,0,0,1054436,26hx6NsKf1,Prontuário,web,2.0.0,,,
EJ9WJsI2IZ,2020-04-17 21:06:24,GiI8HUwVpq,mel,Canino,Pinscher,Fêmea,,Branca,Dócil,0,0,1054435,26hx6NsKf1,Prontuário,web,2.0.0,,,
ymn73pLwJQ,2019-10-09 18:48:22,H9sC3xClw1,Nina,Canino,Shih tzu,Fêmea,,Bicolores,Dócil,1,0,997952,26hx6NsKf1,Prontuário,web,2.0.0,,,
hBpiDok2lZ,2019-05-11 17:37:23,H9sC3xClw1,alok,Canino,Shih tzu,Macho,,tricolor,Dócil,1,0,1090510,26hx6NsKf1,Prontuário,web,2.0.0,,,
AXmEad7JcU,2014-05-26 16:53:49,HJyMJurBLz,Rabito,Canino,SRD,Macho,,tricolor,Dócil,0,0,1112722,26hx6NsKf1,Prontuário,web,2.0.0,,,
o9q7yxDHSh,2021-03-10 03:00:00,HhXXKdGDKT,Pintado,Felino,SRD,Macho,,,Agressivo,0,0,1000107,26hx6NsKf1,Prontuário,web,2.0.0,,,
awqKazcHhF,2013-04-06 11:52:02,I1ReQdqHv9,Fiona,Felino,SRD,Fêmea,,Tigradoa (Brindle),Dócil,1,0,1038390,26hx6NsKf1,Prontuário,web,2.0.0,Vomitou bastante no dia anterior da consulta - convive com cães com Leishmaniose. Foi trocado o sachê e ela vomitou. A tutora colocou bicarbonato na água. ,,
Aax8tA5kPB,2013-02-28 03:00:00,ITTb9IesM4,Princesa,Canino,Pequinês,Fêmea,,Cinza clara,Dócil,0,0,929901,26hx6NsKf1,Prontuário,web,2.0.0,,,
KHcJFtQSmg,2007-02-11 11:11:14,ITTb9IesM4,lindinha,Felino,SRD,Fêmea,,,Agressivo,1,0,959620,26hx6NsKf1,Prontuário,web,2.0.0,,,
9lKoeFqlg8,2021-03-31 16:35:39,IiFxMSrBWr,Tom,Felino,SRD,Macho,,Tigradoa (Brindle),Dócil,1,0,1030233,26hx6NsKf1,Prontuário,web,2.0.0,,,
SFYUZ2pn9k,2010-01-25 02:00:00,Ip4LJWWbDm,Bonner,Canino,Poodle,Macho,,Cinza escura,Dócil,0,0,934328,26hx6NsKf1,Prontuário,web,2.0.0,,,
fQS3cyjtyD,2019-05-17 16:38:19,JCz7PXp7qM,Zeus ,Canino,SRD,Macho,,Marrom,Dócil,0,0,1099211,26hx6NsKf1,Prontuário,web,2.0.0,,,
kIBaSV34Uz,,KILUK7ytKs,PAPADA,Canino,SRD,,,PRETA E BRANCO ,Dócil,,0,1059046,26hx6NsKf1,Prontuário,web,2.0.0,,,
1aCHrA6KF5,2015-03-18 14:15:46,KILUK7ytKs,Preto,Canino,SRD,Macho,,Preta,Covarde,0,0,1011527,26hx6NsKf1,Prontuário,web,2.0.0,,,
h2ChVJjBrO,2008-03-17 11:53:15,KIirXKVVkD,Lili,Canino,Poodle,Fêmea,,Branca,Alerta,1,0,1009484,26hx6NsKf1,Prontuário,web,2.0.0,,,
B4SM0Wm1Gh,2020-08-02 03:00:00,L2Br3xOf72,kira,Canino,SRD,Fêmea,,tricolor,,1,0,1033448,26hx6NsKf1,Prontuário,web,2.0.0,,,
Bee4bU0AQp,2019-10-17 03:00:00,L2Br3xOf72,maia,Canino,Shih tzu,Fêmea,,Bicolores,,1,0,1033464,26hx6NsKf1,Prontuário,web,2.0.0,,,
IMeri3nr50,,LDUhIXGlaF,ATROPELADO (200422),Canino,Chow Chow,Fêmea,,PRETA,Alerta,0,1,1058799,26hx6NsKf1,Prontuário,web,2.0.0,,,
5XJf8nzcuP,2022-01-02 14:39:24,LDUhIXGlaF,Antônia,Felino,SRD,Fêmea,,Tigradoa (Brindle),Ativo,0,0,986331,26hx6NsKf1,Prontuário,web,2.0.0,,,
kVFAN2yLMt,,LDUhIXGlaF,BELINHA 20/04/22,Canino,SRD,Fêmea,,Preta,Dócil,,0,1058815,26hx6NsKf1,Prontuário,web,2.0.0,filhotes mortos,,
PBxxsqJnNL,2017-05-12 17:18:07,LDUhIXGlaF,Brancão 09/05/22,Canino,SRD,Macho,,Branca,Dócil,1,0,1092201,26hx6NsKf1,Prontuário,web,2.0.0,,,
fn2ggm0DA9,,LDUhIXGlaF,CINOMOSE 14/05/22,Canino,SRD,Macho,,,,,1,1097570,26hx6NsKf1,Prontuário,web,2.0.0,,,
SjaQOZYFYm,2012-03-26 03:00:00,LDUhIXGlaF,Jane/fofinho,Canino,SRD,Macho,,Bicolores,Agressivo,0,0,1023131,26hx6NsKf1,Prontuário,web,2.0.0,,,
24S2d8bRYx,2019-02-23 13:20:19,LDUhIXGlaF,Mel,Canino,SRD,Fêmea,,Preta,Agressivo,0,0,976068,26hx6NsKf1,Prontuário,web,2.0.0,,,
QDqFjaCCWk,,LDUhIXGlaF,Mel/edilson 25/04/22,Canino,Labrador retriever,Fêmea,,,,,0,1075931,26hx6NsKf1,Prontuário,web,2.0.0,paciente veio resgatada do sr edilson,,
jw9jgcspG7,2011-02-22 13:43:11,LDUhIXGlaF,PRETINHO,Canino,SRD,Macho,,Preta,Ativo,0,0,975995,26hx6NsKf1,Prontuário,web,2.0.0,,,
q5w7CuuOgK,,LDUhIXGlaF,Pirata 13/05/22,Canino,SRD,,,,,,0,1093976,26hx6NsKf1,Prontuário,web,2.0.0,com tutor ilgon,,
rjdrvAlAOo,,LDUhIXGlaF,Preto,Canino,SRD,Macho,,Preta,Dócil,,0,1106629,26hx6NsKf1,Prontuário,web,2.0.0,,,
uY1MlDK1HV,2008-04-27 03:00:00,LDUhIXGlaF,Veia 15/04,Canino,SRD,Fêmea,,Creme,Dócil,0,0,1068294,26hx6NsKf1,Prontuário,web,2.0.0,,,
JJlOn15hiJ,2022-01-02 03:00:00,LDUhIXGlaF,alfredo,Felino,SRD,Macho,,Tigradoa (Brindle),Ativo,0,1,986107,26hx6NsKf1,Prontuário,web,2.0.0,,,
xWFLhUqJ5Y,2021-11-23 13:20:36,LDUhIXGlaF,bob,Canino,SRD,Macho,,Vermelha amarelada,Dócil,0,0,976035,26hx6NsKf1,Prontuário,web,2.0.0,,,
hnjMcwBDC6,,LDUhIXGlaF,cadelinha atropelada 30/04/22,Canino,SRD,,,,,,1,1075948,26hx6NsKf1,Prontuário,web,2.0.0,atropelada -  feito eutanasia - amputação por atropelamento do membro anterior e do posterior direitos,,
cFZbJLdKHu,2016-05-09 03:00:00,LDUhIXGlaF,dogão 07/05/22,Canino,SRD,Macho,,marrom ,Curioso,1,0,1086291,26hx6NsKf1,Prontuário,web,2.0.0,,,
TWOiN6Ir3O,2022-01-02 03:00:00,LDUhIXGlaF,luke 02/04/22,Canino,American Pit Bull,Macho,,Bicolores,Dócil,0,1,1033017,26hx6NsKf1,Prontuário,web,2.0.0,tutor douglas moura -  cpf endereço bairro esperança casa 1 telefone,,
1FvDT1SADs,2021-07-02 03:00:00,LDUhIXGlaF,luke/douglas 28/04/22,Canino,Pit Bull,Macho,,,,,0,1075954,26hx6NsKf1,Prontuário,web,2.0.0,cachorro da parvo,,
m6Q822V62f,2012-05-02 17:11:51,LDUhIXGlaF,marrom 02/05/22,Canino,SRD,Macho,,marrom,Dócil,0,0,1075969,26hx6NsKf1,Prontuário,web,2.0.0,,,
nc3Pr3LMwo,,LDUhIXGlaF,vacinas 29/04/22,Canino,SRD,,,,,,0,1075942,26hx6NsKf1,Prontuário,web,2.0.0,cachorrinhos que ana pediu para vacinas - 5 filhotes,,
4XRQnp54SE,2022-01-01 03:00:00,LYzH9Gaj61,Agata,Felino,SRD,Fêmea,,Tigradoa (Brindle),Dócil,0,0,985356,26hx6NsKf1,Prontuário,web,2.0.0,,https://vetsmart-parsefiles.s3.amazonaws.com/5eb11a8b8e53daf97f4689833d84d1bf_image.png,
hPJwmB9Jku,2022-01-01 03:00:00,LYzH9Gaj61,Boris,Felino,SRD,Macho,,Bicolores,Dócil,0,0,985351,26hx6NsKf1,Prontuário,web,2.0.0,,https://vetsmart-parsefiles.s3.amazonaws.com/dcfe374c82570f0a31ad49e1d0410f4f_image.png,
kBD1S5ghFb,2009-03-04 16:03:52,LfdOx3Tshr,feia ,Canino,SRD,Fêmea,,marron ,Dócil,1,0,990111,26hx6NsKf1,Prontuário,web,2.0.0,,,
d1sPALseZ1,2010-04-25 20:50:47,Lt2kncQK09,mel,Canino,Labrador retriever,Fêmea,,Creme,Dócil,0,0,1065519,26hx6NsKf1,Prontuário,web,2.0.0,,,
uxyvaujSBf,2016-05-18 03:00:00,Lvppzo3WJ0,Lessie,Canino,Border Collie,Fêmea,,Bicolores,Alerta,0,0,1101406,26hx6NsKf1,Prontuário,web,2.0.0,,,
l3ZjpraSYz,2022-03-15 00:07:11,M9eMkAhHf0,Channel,Canino,Yorkshire Terrier,Fêmea,,,Dócil,0,0,1096052,26hx6NsKf1,Prontuário,web,2.0.0,,,
HxVDW8hKXB,2022-01-25 03:00:00,MChKTgJm1y,Spike,Canino,Shih tzu,Macho,,Bicolores,Dócil,0,0,1021673,26hx6NsKf1,Prontuário,web,2.0.0,,,
1Sxfel9EL5,2020-02-20 02:16:38,MFeTs5TnPZ,Nala,Canino,Pequinês,Fêmea,,Preta, Fulva e Branco,Dócil,0,0,973837,26hx6NsKf1,Prontuário,web,2.0.0,,,
Zg7t7s5k2L,2015-05-25 19:06:17,NAKDbEB7ca,Diana ,Canino,Labrador retriever,Fêmea,,,Dócil,,0,1111516,26hx6NsKf1,Prontuário,web,2.0.0,,,
G6WCGua4w1,2012-04-04 19:02:45,NEuehbGxGs,Lessie,Canino,Poodle,Fêmea,,Branca,Dócil,,0,1035850,26hx6NsKf1,Prontuário,web,2.0.0,,,
1E4edftsaG,2013-02-17 17:26:20,NG127ddTcD,tudi,Felino,SRD,Macho,,Siames,Dócil,0,0,969324,26hx6NsKf1,Prontuário,web,2.0.0,,,
Iv06lKROw4,,NUy5euVmEL,Fofucha,Canino,Poodle,Fêmea,,,,0,0,1061402,26hx6NsKf1,Prontuário,web,2.0.0,,,
kS45BWerMS,2022-01-28 03:00:00,NgbPFlQcRb,Lupy,Canino,SRD,Macho,,,,,0,989026,26hx6NsKf1,Prontuário,web,2.0.0,,,
avXUQmO6bE,2020-04-07 13:52:21,NgbPFlQcRb,Nina,Canino,SRD,Fêmea,,Bicolores,Covarde,1,0,1040637,26hx6NsKf1,Prontuário,web,2.0.0,,,
dhGEP9R1SQ,2020-12-04 03:00:00,Obwqu6C5S0,Poli,Canino,Pinscher,Fêmea,,preto, cinza e bege,Dócil,0,0,949907,26hx6NsKf1,Prontuário,web,2.0.0,,,
RGOzkfCorw,2021-07-30 13:56:44,OqqpAJOG74,Poze,Canino,American Pit Bull,,,Cinza clara,Dócil,0,0,1028248,26hx6NsKf1,Prontuário,web,2.0.0,,,
excNi7ZRNf,2020-05-14 14:36:21,OzfGCx8p66,sakura,Felino,SRD,Fêmea,,Bicolores,Alerta,1,0,1095302,26hx6NsKf1,Prontuário,web,2.0.0,,,
CCC9I4j3KZ,,OzfGCx8p66,tuca,Canino,Pinscher,Fêmea,,marrom,Alerta,0,0,1095299,26hx6NsKf1,Prontuário,web,2.0.0,,,
pRZutjxVCV,2013-08-07 14:41:22,PJYAiR3AO0,Lorde,Canino,Shih tzu,Macho,,Tricolor,Dócil,0,0,993567,26hx6NsKf1,Prontuário,web,2.0.0,,,
BDyltTH0EP,,PL35DMx1vF,Meivin,Felino,SRD,Macho,,,Dócil,,0,1106630,26hx6NsKf1,Prontuário,web,2.0.0,,,
yTEoVLzOyD,2021-09-21 03:00:00,PTdb8WHBzs,Luke,Canino,SRD,Macho,,,Dócil,0,0,928090,26hx6NsKf1,Prontuário,web,2.0.0,paciente será castrado dia 24/01,,
zcnBEmxk73,2018-03-31 18:04:34,Pr2lOYYlh8,Tufo,Canino,Shih tzu,Macho,,fulva e branca,Alerta,0,0,1030471,26hx6NsKf1,Prontuário,web,2.0.0,,,
LAs7ocyAoS,2007-05-15 03:00:00,PySPgoAYSl,Laika,Canino,Border Collie,Fêmea,,Bicolores,Dócil,1,1,1021492,26hx6NsKf1,Prontuário,web,2.0.0,,,
Qvtj3mPIPn,2008-04-30 14:12:13,Pz7vLYiA1V,Belinha,Canino,Shih tzu,Fêmea,,tricolor,Dócil,1,0,1073772,26hx6NsKf1,Prontuário,web,2.0.0,,,
TVTAwlyyjc,2017-05-19 13:07:39,QIu9AGG5Sw,Aurora,Canino,Poodle,Fêmea,,Branca,Dócil,1,0,1102140,26hx6NsKf1,Prontuário,web,2.0.0,,,
REePB3KGgG,2017-11-06 02:00:00,Qlf1UuDrG1,mel,Canino,SRD,Fêmea,,tricolor,Alerta,0,0,1082264,26hx6NsKf1,Prontuário,web,2.0.0,,,
HxUkDcVBBa,2016-01-22 02:00:00,Sv2orDjMIy,Bambi,Canino,SRD,Macho,,Marrom,Dócil,1,0,929640,26hx6NsKf1,Prontuário,web,2.0.0,10kghistórico de epilepsia,,
R9JshNIuFi,2021-05-05 17:49:35,Sv2orDjMIy,JAKI,Canino,SRD,Macho,,MARROM,Dócil,1,0,1081418,26hx6NsKf1,Prontuário,web,2.0.0,,,
CKnFnXIJwX,2015-07-14 03:00:00,Sv2orDjMIy,MOLLY,Canino,SRD,Fêmea,,Amarela e BRANCA,Dócil,,0,928736,26hx6NsKf1,Prontuário,web,2.0.0,PACIENTE TÊM ALTERAÇÃO NA COLUNA - RELATOS DO TUTOR ALGIA .,https://vetsmart-parsefiles.s3.amazonaws.com/535ff0481f1906347854d1df0a1e317b_image.png,
6wNxuy5kYg,2021-09-14 03:00:00,TRxUaqX0pg,Mitzi,Felino,SRD,Fêmea,,Tigradoa (Brindle),Alerta,1,0,1094687,26hx6NsKf1,Prontuário,web,2.0.0,,,
FEAvWmmeFu,2014-05-23 17:01:50,TVd8x8hpwu,Thor,Canino,Lhasa apso,Macho,,Bicolores,Agressivo,1,0,1107535,26hx6NsKf1,Prontuário,web,2.0.0,,,
qdvN4SPRdG,2021-11-12 03:00:00,Tbd6GevfI6,Schnee,Canino,Spitz alemão,Fêmea,,Creme,Dócil,0,0,922912,26hx6NsKf1,Prontuário,web,2.0.0,,,
zMQThGEvJw,2021-09-08 20:14:49,Tl53AFT8bd,tedy,Canino,SRD,Macho,,branco e caramelo,Dócil,0,0,955604,26hx6NsKf1,Prontuário,web,2.0.0,,,
LdgYFyAZwn,2021-02-25 21:02:21,U2fEbxYkoI,aiwar,Canino,Pastor Alemão,Macho,,Bicolores,Alerta,0,0,982410,26hx6NsKf1,Prontuário,web,2.0.0,,,
K3oGcyVeiS,2022-12-05 03:00:00,UB7uMcfYDx,amora,Canino,Shih tzu,Fêmea,,Branca,Dócil,0,0,1024408,26hx6NsKf1,Prontuário,web,2.0.0,,,
3C10NQ4bGd,2021-12-07 03:00:00,VZKSkdBagL,baruk,Canino,Pit Bull,Macho,,Branca,Dócil,0,0,1065731,26hx6NsKf1,Prontuário,web,2.0.0,,,
Utxcz4BtiI,,VmUsFoksGB,ZOE,Canino,SRD,Fêmea,,,,,0,979451,26hx6NsKf1,Prontuário,web,2.0.0,,,
axk5paqt68,2021-03-23 03:00:00,W9xBp4dJOk,Luna,Canino,SRD,Fêmea,,,Dócil,0,0,1018124,26hx6NsKf1,Prontuário,web,2.0.0,Castração,,
MlDCbMzylJ,2020-12-13 14:20:51,X2tBw3rYEF,bastião,Canino,Shih tzu,Macho,,Bicolores,Dócil,0,0,1049768,26hx6NsKf1,Prontuário,web,2.0.0,,,
bJ6tFdFpzw,2017-04-12 14:20:13,YBPNqaQZLx,mintz,Felino,SRD,Macho,,Bicolores,Alerta,1,0,1047976,26hx6NsKf1,Prontuário,web,2.0.0,,,
v4JjgQwCho,2021-05-17 03:00:00,Yhh4aJH6ES,Roque ,Canino,Shih tzu,Macho,,branco, cinza e creme,Dócil,0,0,928157,26hx6NsKf1,Prontuário,web,2.0.0,,,
5WfZWaAGf0,2015-05-02 03:00:00,ZaxT4Pl0xi,aquiles,Canino,Maltês,Macho,,Branca,Dócil,0,0,945015,26hx6NsKf1,Prontuário,web,2.0.0,,,
C69U8oGJki,2018-02-18 12:54:09,ZyeQt7WT3w,doli,Canino,Dachshund,Fêmea,,preta e caremelo,Dócil,0,0,970363,26hx6NsKf1,Prontuário,web,2.0.0,,,
RON3Wf6PTV,2019-03-04 11:29:05,a4sNlRDIvW,Gil,Canino,SRD,Macho,,marrom,Hiperativo,0,0,989432,26hx6NsKf1,Prontuário,web,2.0.0,,,
76LM7BQRV4,2015-04-22 03:00:00,aEp3OtCd4H,chico,Canino,Shih tzu,Macho,,tricolor,Dócil,1,0,940099,26hx6NsKf1,Prontuário,web,2.0.0,,,
TkGs2q7xNx,2013-05-14 03:00:00,adsPxNphcf,Pinsher Tutinha ,Canino,Pinscher,Fêmea,,Preta,Feroz,1,0,1096050,26hx6NsKf1,Prontuário,web,2.0.0,,,
rZ0IO9IrOb,2013-06-20 03:00:00,bFOxq7ZbPi,Abigail (Aby),Canino,SRD,Fêmea,,Preta e caramelo,,1,0,926337,26hx6NsKf1,Prontuário,web,2.0.0,alérgica a enrofloxacin e corantes alimentíciosDASP ,https://vetsmart-parsefiles.s3.amazonaws.com/dccbfd1dd3282e99f81bfc9fc5b4bc85_image.png,
xkBg9YMg4L,2015-01-20 02:00:00,bFOxq7ZbPi,Filomena Maria (fifi),Canino,SRD,Fêmea,,Branca, preta e caramelo,Assustado,1,0,926350,26hx6NsKf1,Prontuário,web,2.0.0,,https://vetsmart-parsefiles.s3.amazonaws.com/9139233360c95e0ac1f2c0b46d21adcf_image.png,
NOOjctex1N,2013-01-20 02:00:00,bFOxq7ZbPi,Maxwell Antenor (MAX),Canino,SRD,Macho,,Branco e preto (manchado),Dócil,1,0,926346,26hx6NsKf1,Prontuário,web,2.0.0,,https://vetsmart-parsefiles.s3.amazonaws.com/c4c8debbd7126e81f4d99f795a55e3f0_image.png,
pkUByvCQeB,,bFOxq7ZbPi,Penélope,Canino,SRD,Fêmea,,marrom,Dócil,,0,1100042,26hx6NsKf1,Prontuário,web,2.0.0,,,
6wZmYDSU76,2012-01-20 02:00:00,bFOxq7ZbPi,Raul Rodolfo,Canino,SRD,Macho,,branco e preto,,1,0,926356,26hx6NsKf1,Prontuário,web,2.0.0,,https://vetsmart-parsefiles.s3.amazonaws.com/1b2843120437cbff17c4c3d361606f8f_image.png,
18uzJ2fFuD,2021-12-16 13:00:58,cASnhpklgi,bolt,Canino,Yorkshire Terrier,Macho,,tricolor,Dócil,0,0,1053289,26hx6NsKf1,Prontuário,web,2.0.0,,,
7zsqm8Wf1j,2012-04-22 03:00:00,ciW6JMGUAQ,manuel,Felino,SRD,Macho,,Preta,Alerta,1,1,1061780,26hx6NsKf1,Prontuário,web,2.0.0,,,
pegcGfr2L8,2021-11-25 20:27:32,d16aDCKrCJ,Frodo,Felino,SRD,Macho,,Bicolores,Dócil,0,0,934301,26hx6NsKf1,Prontuário,web,2.0.0,,,
JQsN6CEskH,2021-02-04 03:00:00,d16aDCKrCJ,Lubi,Felino,SRD,Macho,,Preta,Dócil,1,0,949601,26hx6NsKf1,Prontuário,web,2.0.0,,,
FMtDoDf3TB,2018-02-04 18:24:38,d16aDCKrCJ,Piu-piu,Felino,SRD,Macho,,braco e preto (frajo),Assustado,1,0,949664,26hx6NsKf1,Prontuário,web,2.0.0,,,
Bk5H4u1UKK,2021-12-08 03:00:00,dP1KOvqbHA,KIRA,Canino,SRD,,,TRICOLOR,Dócil,0,0,1042623,26hx6NsKf1,Prontuário,web,2.0.0,,,
LYBEuuaf6r,2022-02-12 12:15:20,e3bplyl5BA,Max,Canino,Shih tzu,Macho,,tricolor,Dócil,0,0,1091460,26hx6NsKf1,Prontuário,web,2.0.0,,,
NDR7cM91UY,2018-01-07 02:00:00,eOecViwUtC,Joaquim,Canino,Shih tzu,Macho,,Bicolores,Assustado,1,0,952071,26hx6NsKf1,Prontuário,web,2.0.0,,,
aU6roWL6pE,2012-04-08 03:00:00,fGtbQL4KyG,FIONA,Canino,SRD,Fêmea,,Bicolores,Dócil,1,0,1042395,26hx6NsKf1,Prontuário,web,2.0.0,,,
hFlQMnLeAI,2014-05-15 03:00:00,fJPKgf9E21,Preta ,Canino,Dachshund,Fêmea,,Preta,Dócil,0,1,1096131,26hx6NsKf1,Prontuário,web,2.0.0,,,
nLp65h6kCb,2021-08-29 12:10:47,fLl6PVLZJf,laika,Canino,Pit Bull,Fêmea,,Bicolores,Dócil,0,0,939649,26hx6NsKf1,Prontuário,web,2.0.0,,,
vaKun0Iid8,2020-04-09 03:00:00,fbtbTYAIxN,Tobby,Canino,SRD,Macho,,tricolor,Dócil,0,0,1044007,26hx6NsKf1,Prontuário,web,2.0.0,,,
LnVybH9UJQ,2014-05-21 05:36:28,gNhcM5R18O,TULI,CAO,SRD,Fêmea,,MARROM,Dócil,1,0,1104984,26hx6NsKf1,Prontuário,web,2.0.0,,,
5Gz8dQpCZT,2022-02-27 03:00:00,gP7nIxsogY,Cacau,Canino,Yorkshire Terrier,Fêmea,,Bicolores,Dócil,0,0,1055958,26hx6NsKf1,Prontuário,web,2.0.0,,,
DM87Cu1boG,2017-02-16 17:40:00,gP7nIxsogY,duna,Canino,Boxer,Fêmea,,,Dócil,1,0,967820,26hx6NsKf1,Prontuário,web,2.0.0,,,
l4f77aQC3i,2021-05-09 20:22:58,gP7nIxsogY,theodore,Canino,Yorkshire Terrier,Macho,,tricolor,Dócil,0,0,1087340,26hx6NsKf1,Prontuário,web,2.0.0,,,
5DgxBOhGU9,,gaNjT3MCKH,banzé,Canino,SRD,,,,,,0,1068847,26hx6NsKf1,Prontuário,web,2.0.0,,,
xjLAL867UA,2021-01-29 12:44:37,hn9C2qloZX,euro,Canino,Pastor Alemão,Macho,,,Dócil,0,0,939754,26hx6NsKf1,Prontuário,web,2.0.0,,,
Sxk9ykIHfX,2016-03-15 03:00:00,j4VQfmYzXx,Laica,Canino,SRD,Fêmea,,Preta,Alerta,0,0,1006017,26hx6NsKf1,Prontuário,web,2.0.0,,,
gMCyfzNcDM,2013-02-16 02:00:00,jFJFg2epjA,preta,Canino,SRD,Fêmea,,Preta,Alerta,0,1,968020,26hx6NsKf1,Prontuário,web,2.0.0,,,
mZfmYj3kOl,2009-03-31 03:00:00,jXCfpm6rln,Branquinha,Canino,SRD,Fêmea,,Amarela,Dócil,0,0,1030637,26hx6NsKf1,Prontuário,web,2.0.0,Unha quebrada.,,
gh20hwE87i,2015-03-12 12:40:46,jcMrzDExCw,Brenda,Canino,SRD,Fêmea,,Bicolores,Covarde,1,0,1002517,26hx6NsKf1,Prontuário,web,2.0.0,paciente com sobrepeso - tem alergias de pele e esta a bastante tempo com otite. ,,
b6SnY4yIqD,2011-03-31 17:59:19,kwOX3gyA9i,morena ,Canino,Border Collie,Fêmea,,Preta, Fulva e Branco,Alerta,0,0,1030456,26hx6NsKf1,Prontuário,web,2.0.0,,,
9cCYk9iQK4,2014-01-29 02:00:00,l1R7EgO8uM,scobby,Canino,Beagle,Macho,,Bicolores,Dócil,0,0,939961,26hx6NsKf1,Prontuário,web,2.0.0,,,
AOg717d2Gg,2017-03-31 17:56:06,lvt44ZWeOg,Schreck,Felino,SRD,Macho,,siames ,Dócil,1,1,1030445,26hx6NsKf1,Prontuário,web,2.0.0,,,
xplrzcFC03,2021-10-10 03:00:00,mZ4uZBkG51,Xerife,Canino,Shih tzu,Macho,,Tricolor,Hiperativo,,0,1085799,26hx6NsKf1,Prontuário,web,2.0.0,,,
snJGuiHoTW,2009-02-21 20:14:52,mezzR960JP,benji,Canino,SRD,Macho,,marrom,Dócil,0,0,975153,26hx6NsKf1,Prontuário,web,2.0.0,,,
lvvvd5YYSv,2013-05-14 03:00:00,nUQ0Ok4Uje,Frederico,Canino,Shih tzu,Macho,,cinza, branco e caramelo,Dócil,0,0,978615,26hx6NsKf1,Prontuário,web,2.0.0,,,
6jH6xSuIJU,2016-08-03 03:00:00,nUQ0Ok4Uje,Olivia,Canino,Shih tzu,Fêmea,,Preta, Fulva e Branco,Dócil,1,0,978599,26hx6NsKf1,Prontuário,web,2.0.0,,,
EPE8CiNcxG,2016-01-10 02:00:00,nUQ0Ok4Uje,THEO,Canino,Shih tzu,Macho,,BRANCO , CINZA,Dócil,,0,978573,26hx6NsKf1,Prontuário,web,2.0.0,,,
QKegk8rCwS,2020-01-13 03:00:00,nUQ0Ok4Uje,Valentina,Canino,Spitz alemão,Fêmea,,Dourada,Dócil,1,0,978607,26hx6NsKf1,Prontuário,web,2.0.0,,,
K6yS0fEXvG,2022-03-26 03:00:00,nUofnkfsVG,coockie,Canino,Shih tzu,Macho,,Bicolores,Dócil,0,0,1082370,26hx6NsKf1,Prontuário,web,2.0.0,,,
PNPZSaGNz8,2015-05-11 20:28:12,oONNXxqCud,Mel,Canino,Dachshund,Fêmea,,marrom,Alerta,1,0,1091108,26hx6NsKf1,Prontuário,web,2.0.0,,,
7POTGkGhwb,2011-02-08 10:32:39,pMk7bv5mRm,jerry,Canino,Pinscher,Macho,,,,,0,954033,26hx6NsKf1,Prontuário,web,2.0.0,,,
AhRe5vCEvC,2019-11-30 03:00:00,pk7RjqVESU,flash,Canino,SRD,Macho,,preto e caramelo,Assustado,1,0,955445,26hx6NsKf1,Prontuário,web,2.0.0,,,
iaFNfaXr9j,2022-01-21 03:00:00,pm5OqxRsZ1,simba,Canino,Dogo argentino,Macho,,Branca,Dócil,0,0,1025061,26hx6NsKf1,Prontuário,web,2.0.0,,,
m39e3nhkSA,2022-02-14 03:00:00,qdY8PVjnEw,GORDINHA,Canino,SRD,Fêmea,,Preta,Dócil,0,0,1080140,26hx6NsKf1,Prontuário,web,2.0.0,,,
4E9SQz85y2,2022-02-14 03:00:00,qdY8PVjnEw,JADE,Canino,SRD,Fêmea,,Preta,Dócil,0,0,1080142,26hx6NsKf1,Prontuário,web,2.0.0,,,
ZHBpGxfWb0,2022-02-14 03:00:00,qdY8PVjnEw,TAURA,Canino,SRD,Macho,,MARROM,Dócil,0,0,1080144,26hx6NsKf1,Prontuário,web,2.0.0,,,
WRJ20yn1Cx,2014-11-11 02:00:00,r7K32DPWJL,Chili,Canino,Shih tzu,Fêmea,,Cinza clara,Dócil,1,0,923844,26hx6NsKf1,Prontuário,web,2.0.0,,,
nOEYaZnhDE,2013-03-10 03:00:00,r7K32DPWJL,Meg,Canino,Shih tzu,Fêmea,,Avelã,Dócil,1,0,923856,26hx6NsKf1,Prontuário,web,2.0.0,,,
BVScCWuQp9,2018-03-30 18:22:47,r7K32DPWJL,Nadine,Canino,SRD,Fêmea,,,Dócil,1,0,1028874,26hx6NsKf1,Prontuário,web,2.0.0,,,
GS2AWrWxS3,,r7K32DPWJL,chelsi,Canino,Shih tzu,Fêmea,,,,,0,1056096,26hx6NsKf1,Prontuário,web,2.0.0,,,
mEwFYx8Rv8,2018-08-13 03:00:00,rhCFioeQGE,Cacau,Canino,Shih tzu,Fêmea,,Castanha,Dócil,1,0,1011259,26hx6NsKf1,Prontuário,web,2.0.0,,,
G2DJiOMwIx,2017-05-13 17:59:15,sPythpAD2n,pirata,Canino,SRD,Fêmea,,tricolor,Dócil,1,0,1093945,26hx6NsKf1,Prontuário,web,2.0.0,,,
E2iQZYD8P5,2020-12-25 17:01:38,sf3Zs9gg64,BANGUELA,Felino,SRD,Macho,,PRETO,Dócil,1,0,981769,26hx6NsKf1,Prontuário,web,2.0.0,,https://vetsmart-parsefiles.s3.amazonaws.com/7cfff3009a7cc628b8a78a2f2e0af604_image.png,
85Yqtf03BP,2021-02-25 03:00:00,sf3Zs9gg64,FLOR ,Canino,SRD,Fêmea,,BRANCA/PRETA,Dócil,0,0,981781,26hx6NsKf1,Prontuário,web,2.0.0,,https://vetsmart-parsefiles.s3.amazonaws.com/587316b16e6b747fd109e5c6d6079d20_image.png,
fIMbduMnaK,2021-02-25 17:03:00,sf3Zs9gg64,FURIA,Felino,SRD,Macho,,Branca,Dócil,1,0,981775,26hx6NsKf1,Prontuário,web,2.0.0,,https://vetsmart-parsefiles.s3.amazonaws.com/702282506607a426080380da4dc02f97_image.png,
zMs9GewFVP,2020-02-25 17:06:22,sf3Zs9gg64,OZZY,Canino,SRD,Macho,,BRANCO/PRETO,Dócil,0,0,981787,26hx6NsKf1,Prontuário,web,2.0.0,,https://vetsmart-parsefiles.s3.amazonaws.com/a36e242acd51ad4ea613f8025b2c77aa_image.png,
HieFLCzFFh,2020-02-25 17:07:52,sf3Zs9gg64,PITY,Canino,SRD,Fêmea,,Amarela,Dócil,1,0,981795,26hx6NsKf1,Prontuário,web,2.0.0,,https://vetsmart-parsefiles.s3.amazonaws.com/d004c0b086a9705e36224095181d06df_image.png,
H9kRBKpgaY,2012-03-20 14:18:42,soY6e1RBZD,belinha,Canino,SRD,Fêmea,,Cinza clara,Dócil,1,0,1014190,26hx6NsKf1,Prontuário,web,2.0.0,,,
iliCGQDbv7,2021-10-19 03:00:00,stJRfCNnOF,Mel,Felino,Persa,Fêmea,,Branca,Dócil,1,0,955067,26hx6NsKf1,Prontuário,web,2.0.0,,https://vetsmart-parsefiles.s3.amazonaws.com/280aa16cae1794aadc85fd349280e1db_image.png,
293OTJKFrq,2021-12-28 03:00:00,swvBMDdx32,Laka,Canino,Dogo argentino,Fêmea,,Branca,Dócil,0,0,928130,26hx6NsKf1,Prontuário,web,2.0.0,,,
QbsHz2DifD,2016-05-07 21:42:12,sxyXEUYiZB,DOGÃO,Canino,SRD,Macho,,MARROM,Assustado,,0,1085418,26hx6NsKf1,Prontuário,web,2.0.0,,,
TARTBjVeMJ,2022-02-09 16:50:12,tR579amjDt,mia,Felino,SRD,Fêmea,,tricolor,Dócil,0,0,1086590,26hx6NsKf1,Prontuário,web,2.0.0,,,
VeirULw9uZ,2021-02-16 19:16:39,u5J6GVHKzr,Cachorro,Canino,SRD,Macho,,preto e branco,Dócil,0,0,967934,26hx6NsKf1,Prontuário,web,2.0.0,,,
sgAflB1F2C,2021-07-09 03:00:00,u5J6GVHKzr,Guria,Canino,SRD,Fêmea,,Preta,Dócil,0,0,997975,26hx6NsKf1,Prontuário,web,2.0.0,,,
ZFj7TIaI2V,2020-04-08 03:00:00,u5J6GVHKzr,HAKO,Canino,Pastor Belga Malinois,Macho,,Bicolores,Dócil,1,0,1042283,26hx6NsKf1,Prontuário,web,2.0.0,,,
ny49NbQpc9,2021-12-25 03:00:00,u5J6GVHKzr,Mico,Felino,SRD,Macho,,Branca,Ativo,0,0,982353,26hx6NsKf1,Prontuário,web,2.0.0,encontrou na rua bem pequeno,,
fhTLe03yrw,2018-03-04 16:33:22,u5J6GVHKzr,Pretinha,Canino,SRD,Fêmea,,Preta,Alerta,0,0,990159,26hx6NsKf1,Prontuário,web,2.0.0,,,
jKCmJvgTra,2021-12-09 03:00:00,u5J6GVHKzr,Sementinha,Canino,SRD,Fêmea,,marrom,Dócil,0,0,997128,26hx6NsKf1,Prontuário,web,2.0.0,,,
7FyzOwsyE8,2016-05-26 14:07:05,u5J6GVHKzr,Wiki,Canino,SRD,Fêmea,,Castanha,Alerta,1,0,1112410,26hx6NsKf1,Prontuário,web,2.0.0,,,
4QRISYXpC7,2017-03-31 18:10:42,u5J6GVHKzr,benta,Canino,Border Collie,Fêmea,,tricolor,Dócil,1,0,1030493,26hx6NsKf1,Prontuário,web,2.0.0,,,
YvQAfOAeNV,2016-03-04 17:56:17,u5J6GVHKzr,malhada,Canino,SRD,Fêmea,,Malhada,Dócil,1,0,990384,26hx6NsKf1,Prontuário,web,2.0.0,,,
pUwJ7fxYza,2016-04-14 03:00:00,ukumCl1anb,nuno,Felino,SRD,Macho,,frajola,Dócil,1,0,1051824,26hx6NsKf1,Prontuário,web,2.0.0,,,
AE2Up8txuP,2011-04-17 03:00:00,vMAiywCrPc,bianca,Canino,SRD,Fêmea,,Branca,Alerta,0,0,1054434,26hx6NsKf1,Prontuário,web,2.0.0,,,
nHZ7gwA9g8,2021-10-10 03:00:00,vMpx0IiXTv,Tedy,Canino,Shih tzu,Macho,,creme e preto,Dócil,0,0,950347,26hx6NsKf1,Prontuário,web,2.0.0,,,
9wF8RzpNio,2021-11-18 03:00:00,vSwDSG36bk,moliver,Canino,Pit Bull,Macho,,,Dócil,0,0,922644,26hx6NsKf1,Prontuário,web,2.0.0,,https://vetsmart-parsefiles.s3.amazonaws.com/3a777effa33d14aa8961cca276d5745d_image.png,
vJQVAAXBPV,2010-03-11 14:08:36,vXk04nVBKz,Kelinho,Canino,Poodle,Fêmea,,Branca,,0,0,1001007,26hx6NsKf1,Prontuário,web,2.0.0,Tumor de mama voltou - já foi operada uma vez para retirar e voltou,,
sz826qwP9b,2021-06-28 14:18:53,vuBn5bkktO,Luke,Canino,American Pit Bull,Macho,,Branca,Alerta,0,0,1070067,26hx6NsKf1,Prontuário,web,2.0.0,,,
McEndrSb7s,2020-05-03 19:24:29,wIaF3VyMeS,MEL,Felino,SRD,Fêmea,,Bicolores,Dócil,1,0,1078252,26hx6NsKf1,Prontuário,web,2.0.0,,,
BASe7HqKfQ,,wWahtG2KH0,pity,Canino,SRD,,,preta,Assustado,0,0,1067496,26hx6NsKf1,Prontuário,web,2.0.0,,,
mXT3K1TbmG,,xC4cnTvpmk,Bianca,Canino,SRD,Fêmea,,,Dócil,1,0,1121790,26hx6NsKf1,Prontuário,web,2.0.0,,,
xROuvyzGVF,2012-01-21 02:00:00,xC4cnTvpmk,Laika,Canino,SRD,Fêmea,,Preta, Fulva e Branco,Dócil,1,0,927876,26hx6NsKf1,Prontuário,web,2.0.0,,https://vetsmart-parsefiles.s3.amazonaws.com/d38e6b1d8f502e1099b9343f8ed31379_image.png,
GiPQD3BElI,2019-03-11 03:00:00,xWWHpNvOv6,Soneca,Felino,SRD,Macho,,Amarela,Dócil,0,1,1000695,26hx6NsKf1,Prontuário,web,2.0.0,Chegou ictérico - com prolapso de terceira pálpebra - incordenado - apresentou diferença entre midríase e miose nos olhos - com vômito - testou positivo para FELV.,,
JjguwIdSg5,2019-05-05 13:12:47,yDDmkVkKMs,preta,Canino,Pug,Fêmea,,Preta,Hiperativo,0,0,991505,26hx6NsKf1,Prontuário,web,2.0.0,,,
hCGtN2YCx4,2021-11-05 03:00:00,yHN6HDyuwK,Tigrinho,Felino,SRD,Macho,,Tigradoa (Brindle),Alerta,0,0,1075216,26hx6NsKf1,Prontuário,web,2.0.0,,,
mAzFsUmWAL,2010-04-19 14:34:34,yrzlxDPCPO,bela,Canino,srd,Fêmea,,Branca,Dócil,,0,1056996,26hx6NsKf1,Prontuário,web,2.0.0,,,
XwF2mKn3b1,2017-03-04 03:00:00,z0WywITvJR,NICK,Canino,Shih tzu,Macho,,Bicolores,DOCIL,0,0,990331,26hx6NsKf1,Prontuário,web,2.0.0,,,
bGt9QpuIua,2022-01-01 03:00:00,z6QMs7FSD6,Magali,Felino,SRD,Fêmea,,Bicolores,Dócil,0,0,985616,26hx6NsKf1,Prontuário,web,2.0.0,,,
AcaEsFbSYp,2014-05-21 12:36:56,zO9znKeFIC,Pequeno,Canino,SRD,Macho,,Amarela,Assustado,1,0,1105148,26hx6NsKf1,Prontuário,web,2.0.0,alergico a pulga-dasp,,";

	//EXPLODE AS LINHAS QUANDO PULAR LINHA
	$linha	=	explode("\n", $conteudo);
	for($i = 0; $i < sizeof($linha); $i++) {

		$var = trim($linha[$i]);	
		$linhas = explode(",", $var);
		//print_r($linhas);


		$nascimento = $linhas[1];
		$id_petConsumidor = $linhas[2];
		$nome_pet = $linhas[3];
		$nome_especie = $linhas[4];
		$nome_raca = $linhas[5];
		$nome_sexo = $linhas[6];
		$cor_pet = $linhas[8];
		$tempertamento_pet = $linhas[9];
		$castrado_pet = $linhas[10];
		$obs = $linhas[17];
		$imagem_pet = $linhas[18];

/*
		echo("id_consumidor: $id_petConsumidor | ");
		echo("nome: $nome_pet | ");
		echo("nome especie: $nome_especie | ");
		echo("raca: $nome_raca | ");
		echo("sexo: $nome_sexo | ");
		echo("cor: $cor_pet | ");
		echo("temperamento: $tempertamento_pet | ");
		echo("castrado: $castrado_pet | ");
		echo("obs: $obs | ");*/
		//echo("img: $imagem_pet <br> ");


		//converter nascimento
		$explode = explode(" ", $nascimento);
		$aniversario = $explode[0];

		//sexo
		if($nome_sexo == 'Macho'){
			$sexo = 2;
		}
		if($nome_sexo== 'Fêmea'){
			$sexo = 1;
		}

		//especie
		if($nome_especie == 'Canino'){
			$especie = 1;
		}
		if($nome_especie == 'Felino'){
			$especie = 2;
		}
		if($nome_especie == 'Equino'){
			$especie = 6;
		}
		if($nome_especie == 'Roedor'){
			$especie = 3;
		}

		//busca raca
		$sql="select * from $base_sel.raca where raca_nome = '$nome_raca'";
		$stm = $pdo->prepare("$sql");	
		$stm->execute();
		while($rst = $stm->fetch(PDO::FETCH_OBJ)){
			$id_raca = $rst->raca_id;
		}

		//castrado
		if($castrado_pet == '1'){
			$castrado = "Sim";
		}else{
			if($castrado_pet == '0'){
				$castrado = "Não";
			}else{
				$castrado = "Sem informação";
			}
		}
		
		
		$observacoes = "Temperamento: $tempertamento_pet | 
		Castrado: $castrado |
		Cor de pelagem: $cor_pet |
		Outras Obs: $obs";

		//busca ID do tutor
		$sql="select * from $base_sel.consumidor where loja1 = '$id_petConsumidor'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		while($rst = $stm->fetch(PDO::FETCH_OBJ)){
			$id_consumidor = $rst->CODIGO_CONSUMIDOR;
		}

		//insere o pet
		$sql="insert into $base_sel.pets (pets_idcliente,pets_nome,pets_especie,pets_raca,pets_sexo,pets_obs,pets_img,pets_dtnascimento,pets_dtcadastro) values (
		'$id_consumidor',
		'$nome_pet',
		'$especie',
		'$id_raca',
		'$sexo',
		'$observacoes',
		'$imagem_pet',
		'$aniversario',
		CURRENT_DATE()

		)";
		$stm = $pdo->prepare($sql);	
		echo($sql."<br>");
		$stm->execute();


	}

