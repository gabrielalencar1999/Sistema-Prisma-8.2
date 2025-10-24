<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tabela de Natureza Operção - CFOP</title>
  <style>
    /* Reset básico */
    * {
      box-sizing: border-box;
    }
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f2f5;
      margin: 0;
      padding: 0;
    }

    /* Header */
    header {
      position: fixed;
      top: 0;
      width: 100%;
      height: 60px;
      background-color: #fff;
      border-bottom: 1px solid #ddd;
      display: flex;
      align-items: center;
      padding: 0 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      z-index: 1000;
    }
    .logo {
      font-weight: bold;
      font-size: 20px;
      color: #0099cc;
      margin-right: 30px;
      user-select: none;
      cursor: default;
    }
    .search-box {
      flex-grow: 1;
      position: relative;
    }
    .search-box input[type="text"] {
      width: 100%;
      padding: 8px 35px 8px 12px;
      border: 1px solid #ccc;
      border-radius: 20px;
      font-size: 14px;
      transition: border-color 0.3s;
    }
    .search-box input[type="text"]:focus {
      border-color: #0099cc;
      outline: none;
    }
    .search-box button {
      position: absolute;
      right: 6px;
      top: 50%;
      transform: translateY(-50%);
      border: none;
      background: transparent;
      cursor: pointer;
      font-size: 18px;
      color: #666;
    }
    .header-icons {
      margin-left: 30px;
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .header-icon {
      font-size: 20px;
      color: #666;
      cursor: pointer;
      user-select: none;
      transition: color 0.3s;
    }
    .header-icon:hover {
      color: #0099cc;
    }

    /* Espaço para o conteúdo não ficar embaixo do header */
    .main-content {
      padding: 80px 20px 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    /* Resto do CSS do container e tabela igual ao anterior */
    .container {
      max-width: 100%;
    }
    .card {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-bottom: 20px;
    }
    .table-container {
      width: 100%;
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    th {
      background-color: #f9f9f9;
      color: #666;
      font-weight: normal;
      user-select: none;
    }
    .sort-icon {
      margin-left: 5px;
      color: #999;
      cursor: pointer;
    }
    .filter-panel {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      padding: 15px;
      margin-bottom: 15px;
      display: none;
    }
    .filter-row {
      display: flex;
      flex-wrap: wrap;
      margin-bottom: 10px;
    }
    .filter-field {
      flex: 1;
      min-width: 200px;
      margin-right: 15px;
      margin-bottom: 10px;
    }
    .filter-field label {
      display: block;
      margin-bottom: 5px;
      color: #666;
      font-size: 14px;
    }
    .filter-field select,
    .filter-field input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    .filter-actions {
      display: flex;
      justify-content: flex-end;
      margin-top: 10px;
    }
    .filter-actions button {
      padding: 8px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      margin-left: 10px;
    }
    .filter-apply {
      background-color: #0099cc;
      color: white;
    }
    .filter-clear {
      background-color: #f0f2f5;
      color: #666;
    }
    .toggle-button {
      margin-bottom: 10px;
      background-color: #0099cc;
      color: white;
      border: none;
      border-radius: 4px;
      padding: 8px 15px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <!-- Header fixo -->
  <header>

     
  </header>

  <!-- Conteúdo principal -->
  <div class="main-content">
    <div class="container">
  <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-pesquisa" id="form-pesquisa">
        <div class="logo">Prisma</div>
        <div class="search-box">
          <input type="text" id="descricaoPesquisa" name="descricaoPesquisa" placeholder="Pesquisar..."  onchange="_pesquisar();"/>
          <button type="button"  title="Pesquisar">&#128269;</button>
         
        </div>
        <div class="header-icons">
          <div class="header-icon" title="Usuário">&#128100;</div>
          <div class="header-icon" title="Notificações">&#128276;</div>
        </div>
        
       </form>
        <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-incluir" id="form-incluir">
          <input type="text" id="nome" name="nome" />
           <button type="button"   title="INCLUIR" onclick="_incluir()">incluir</button>
      </form>
      <!-- Botão para mostrar/esconder filtro -->
      <button id="toggleFilterBtn" class="toggle-button">Mostrar/Esconder Filtros</button>

      <!-- Painel de Filtros -->
      <div class="filter-panel" id="filterPanel">
        <div class="filter-row">
          <div class="filter-field">
            <label>Descrição</label>
            <input type="text" id="filterDescricao" placeholder="Digite a descrição" />
          </div>
          <div class="filter-field">
            <label>CFOP</label>
            <select id="filterCFOP">
              <option value="">Selecione</option>
              <option value="1">CFOP 1</option>
              <option value="2">CFOP 2</option>
              <option value="3">CFOP 3</option>
            </select>
          </div>
          <div class="filter-field">
            <label>Tipo Documento</label>
            <select id="filterTipoDoc">
              <option value="">Selecione</option>
              <option value="1">Tipo 1</option>
              <option value="2">Tipo 2</option>
              <option value="3">Tipo 3</option>
            </select>
          </div>
        </div>
        <div class="filter-row">
          <div class="filter-field">
            <label>Finalidade</label>
            <select id="filterFinalidade">
              <option value="">Selecione</option>
              <option value="1">Finalidade 1</option>
              <option value="2">Finalidade 2</option>
              <option value="3">Finalidade 3</option>
            </select>
          </div>
          <div class="filter-field">
            <label>Data Inicial</label>
            <input type="date" id="filterDataInicial" />
          </div>
          <div class="filter-field">
            <label>Data Final</label>
            <input type="date" id="filterDataFinal" />
          </div>
        </div>
        <div class="filter-actions">
          <button id="clearFilters" class="filter-clear">Limpar</button>
          <button id="applyFilters" class="filter-apply">Aplicar</button>
        </div>
      </div>

      <!-- Botão para mostrar/esconder tabela -->
      <button id="toggleTableBtn" class="toggle-button">Mostrar/Esconder Tabela</button>

      <!-- Tabela -->
      <div class="card" id="tableContainer">
        <div class="table-container">
          <div id="divretorno">
                          <table style="width: 100%;">
                            
                            
                          <table border="1" style="width: 100%; border-collapse: collapse;">
                  <thead>
                    <tr>
                      <th>
                        Descrição
                        <span class="sort-icon" data-column="descricao">↕</span>
                      </th>
                      <th>
                        CODIGO
                        <span class="sort-icon" data-column="cfop">↕</span>
                      </th>
                      <th>
                        Tipo Documento
                        <span class="sort-icon" data-column="tipodoc">↕</span>
                      </th>
                      <th>
                        Finalidade
                        <span class="sort-icon" data-column="finalidade">↕</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody id="tableBody">
                    <?php include("acao_cfoplistar.php"); ?>
                  </tbody>
                </table>
    </div>
        </div>

        <!-- Paginação -->
       <div class="pagination">
  <div class="pagination-info" id="paginationInfo">
    Mostrando 0 de 0 Total 0
  </div>
</div>

<script>
  window.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.getElementById("tableBody");
    const totalLinhas = tableBody.querySelectorAll("tr").length;

    const paginationInfo = document.getElementById("paginationInfo");
    paginationInfo.textContent = `Mostrando 1 até ${totalLinhas} de ${totalLinhas} registros`;
  });
</script>
          <div class="pagination-controls">
            <button class="pagination-button" id="prevPage">&lt;</button>
            <button class="pagination-button" id="nextPage">&gt;</button>
          </div>
        </div>

        <div class="total-info" id="totalInfo">
          Total: R$ 0,00
        </div>
      </div>
    </div>
  </div>
</body>
<script src="../assets/js/jquery.min.js"></script>
  <script>
    /*
    // Mostrar / esconder painel de filtro
    const filterPanel = document.getElementById('filterPanel');
    const toggleFilterBtn = document.getElementById('toggleFilterBtn');
    toggleFilterBtn.addEventListener('click', () => {
      if (filterPanel.style.display === 'block') {
        filterPanel.style.display = 'none';
      } else {
        filterPanel.style.display = 'block';
      }
    });

    // Limpar filtros
    document.getElementById('clearFilters').addEventListener('click', () => {
      document.getElementById('filterDescricao').value = '';
      document.getElementById('filterCFOP').selectedIndex = 0;
      document.getElementById('filterTipoDoc').selectedIndex = 0;
      document.getElementById('filterFinalidade').selectedIndex = 0;
      document.getElementById('filterDataInicial').value = '';
      document.getElementById('filterDataFinal').value = '';
    });

    // Aplicar filtros (simulado)
    document.getElementById('applyFilters').addEventListener('click', () => {
  const descricao   = document.getElementById('filterDescricao').value.trim();
  const cfop        = document.getElementById('filterCFOP').value;
  const tipoDoc     = document.getElementById('filterTipoDoc').value;
  const finalidade  = document.getElementById('filterFinalidade').value;

  // Monta os parâmetros da URL
  const params = new URLSearchParams();
  if (descricao) params.append('descricao', descricao);
  if (cfop) params.append('cfop', cfop);
  if (tipoDoc) params.append('tipoDoc', tipoDoc);
  if (finalidade) params.append('finalidade', finalidade);

  // Redireciona para mesma página com os filtros
  window.location.href = window.location.pathname + '?' + params.toString();
});

    // Mostrar / esconder tabela
    const tableContainer = document.getElementById('tableContainer');
    const toggleTableBtn = document.getElementById('toggleTableBtn');
    toggleTableBtn.addEventListener('click', () => {
      if (tableContainer.style.display === 'none') {
        tableContainer.style.display = 'block';
      } else {
        tableContainer.style.display = 'none';
      }
    });

    // Pesquisa simples (exemplo)
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');

    searchBtn.addEventListener('click', () => {
      const termo = searchInput.value.trim().toLowerCase();
      if (!termo) {
        alert('Digite um termo para pesquisar.');
        return;
      }
      alert(`Pesquisar por: "${termo}" (implementação futura)`);
      // Aqui você pode implementar a pesquisa filtrando os dados da tabela ou enviando para o backend
    });

    // Enter para pesquisar
    searchInput.addEventListener('keyup', (event) => {
      if (event.key === 'Enter') {
        searchBtn.click();
      }
    });

    */
    function _pesquisar(){
     // alert("teste");

         //var $_keyid = "modelo_00002";
        var dados = $("#form-pesquisa :input").serializeArray(); 
        dados = JSON.stringify(dados);
     aguardeListagem('#divretorno');
        $.post("buscardescricao.php", { dados:dados, acao: 0},
            function(result){           
              $("#divretorno").html(result);
              //  $('#datatable-responsive').DataTable();
        });

    }

      function _incluir(){
     // alert("teste");

         //var $_keyid = "modelo_00002";
        var dados = $("#form-incluir :input").serializeArray(); 
        dados = JSON.stringify(dados);
     aguardeListagem('#divretorno');
        $.post("buscardescricao.php", { dados:dados, acao: 1},
            function(result){           
              $("#divretorno").html(result);
              //  $('#datatable-responsive').DataTable();
        });

    }


        function aguardeListagem(id) {
        $(id).html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="../assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
            '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }
  </script>

</html>
