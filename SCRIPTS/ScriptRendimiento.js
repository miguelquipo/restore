document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('insert-form');
  const cedulaInput = document.getElementById('search-cedula');
  const productoInput = document.getElementById('search-producto');
  const cantidadInput = document.getElementById('insert-cantidad');
  const switchCheckbox = document.getElementById("mode-switch");
  const modeText = document.getElementById("mode-text");
  const manualSubmitButton = document.getElementById("manual-submit");

  // Variable para almacenar el estado del modo automático
  let modoAutomatico = localStorage.getItem('modoAutomatico') === 'true';

  switchCheckbox.checked = modoAutomatico;

  // Función para verificar campos y enviar formulario
  function verificarCampos() {
    if (switchCheckbox.checked) {
      if (cedulaInput.value.trim() !== '' && productoInput.value.trim() !== '') {
        // Solo enviar si la cantidad está ingresada
        if (cantidadInput.value.trim() !== '') {
          // Verificar si el formulario ya se envió
          if (form.getAttribute('data-submitted') !== 'true') {
            form.submit();
            form.setAttribute('data-submitted', 'true');
          }
        }
      }
    }
  }

  // Agregar eventos input a los campos
  cedulaInput.addEventListener('input', verificarCampos);
  productoInput.addEventListener('input', verificarCampos);
  cantidadInput.addEventListener('input', verificarCampos);

  // Función para enviar formulario manualmente
  manualSubmitButton.addEventListener('click', function(event) {
    event.preventDefault();
    if (cedulaInput.value.trim() !== '' && productoInput.value.trim() !== '' && cantidadInput.value.trim() !== '') {
      form.submit();
    }
  });

  // Enfocar el campo de cédula al cargar la página
  cedulaInput.focus();

  // Función para manejar el interruptor de modo automático/manual
  switchCheckbox.addEventListener('change', function() {
    // Restablecer estado del formulario
    form.removeAttribute('data-submitted');

    // Actualizar variable del estado del modo automático
    modoAutomatico = switchCheckbox.checked;
    if (switchCheckbox.checked) {
      modeText.innerText = "Modo Automático";
      manualSubmitButton.style.display = "none";
      cedulaInput.setAttribute("onkeydown", "moveToNext(event, 'search-producto')");
      productoInput.setAttribute("onkeydown", "moveToNext(event, 'insert-cantidad')");
      cantidadInput.setAttribute("onkeydown", "moveToNext(event, '')");
      form.removeAttribute('data-submitted');
    } else {
      modeText.innerText = "Modo Manual";
      manualSubmitButton.style.display = "block";
      cedulaInput.removeAttribute("onkeydown");
      form.removeAttribute('data-submitted');
      productoInput.removeAttribute("onkeydown");
      cantidadInput.removeAttribute("onkeydown");
    }

    // Guardar el estado del interruptor en el almacenamiento local
    localStorage.setItem('modoAutomatico', switchCheckbox.checked);
  });

  // Función para mover al siguiente campo en modo automático
  function moveToNext(event, nextFieldId) {
    if (switchCheckbox.checked) {
      var currentField = event.target;
      var fieldValue = currentField.value.trim();
      var key = event.key;

      if (key === 'Enter' && fieldValue !== '') {
        if (nextFieldId !== '') {
          var nextField = document.getElementById(nextFieldId);
          nextField.focus();
        }

        // Evitar que el formulario se envíe múltiples veces
        event.preventDefault();
        event.stopPropagation();

        // Verificar si todos los campos están llenos antes de enviar el formulario
        if (cedulaInput.value.trim() !== '' && productoInput.value.trim() !== '' && cantidadInput.value.trim() !== '') {
          // Solo enviar si la cantidad está ingresada
          if (form.getAttribute('data-submitted') !== 'true') {
            form.submit();
            form.setAttribute('data-submitted', 'true');
          }
        }
      }
    }
  }

  // Verificar si hay un estado guardado en el almacenamiento local y establecer el interruptor en consecuencia
  const modoGuardado = localStorage.getItem('modoAutomatico');
  if (modoGuardado !== null) {
    switchCheckbox.checked = modoGuardado === 'true';
    switchCheckbox.dispatchEvent(new Event('change')); // Disparar evento de cambio para reflejar el estado
  }

  // Resto de tu código...
});



//Mostrardatos por cada hora trancurrida    
document.addEventListener('DOMContentLoaded', function () {
    // Obtener datos iniciales
    obtenerDatos();
  
    // Agregar evento al botón de exportación
    const exportarBtn = document.getElementById('exportar-btn');
    exportarBtn.addEventListener('click', function () {
      exportarExcel();
    });
  
    // Actualizar los datos cada cierto intervalo (por ejemplo, cada 5 minutos)
    setInterval(function () {
      obtenerDatos();
    }, 300000); // 300000 milisegundos = 5 minutos
  });
  
  function obtenerDatos() {
    let url = './PHP/obtener_rendimientos.php';
    fetch(url)
      .then(response => response.json())
      .then(data => {
        mostrarDatosEnTabla(data);
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error);
      });
  }
  
  function mostrarDatosEnTabla(data) {
    const tableBody = document.getElementById('table-body');
  
    // Limpiamos cualquier contenido previo en la tabla
    tableBody.innerHTML = '';
  
    // Iteramos sobre los datos y creamos las filas de la tabla
    data.forEach(rendimiento => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${rendimiento.id_rendimiento}</td>
        <td>${rendimiento.cantidad_vendida}</td>
        <td>${rendimiento.nombre_producto}</td>
        <td>${rendimiento.nombre_trabajador}</td>
        <td>${rendimiento.apellido_trabajador}</td>
        <td>${rendimiento.fecha_registro}</td>
        <td>${rendimiento.hora_registro}</td>
      `;
      tableBody.appendChild(row);
    });
  }
  
  
  // Función para exportar la tabla a Excel
  function exportarExcel() {
    const ws = XLSX.utils.table_to_sheet(document.getElementById('rendimientos-table'));
    // Guardar el archivo
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Rendimientos');
    XLSX.writeFile(wb, 'RendimientosHora.xlsx');
  }

  const cantidadInput = document.getElementById("insert-cantidad");
cantidadInput.addEventListener("input", () => {
  cantidadInput.value = cantidadInput.value.replace(/[^0-9]/g, "");
  if (cantidadInput.value <= 0) {
    cantidadInput.value = 1;
  }
});

  