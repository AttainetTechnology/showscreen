
<!-- Beginning footer -->
<div class="panel-footer">
							&copy; ATTAINET TECHNOLOGY 2022
                        </div>
<!-- End of Footer -->

         </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    
   
	<!-- Funciones creadas por TANTATA -->
	<script src="<?php echo base_url();?>/assets/js/functions.js"></script> 

<script>
    $(document).ready(function(){
        // console.log('que pasa aquí');
        window.addEventListener('gcrud.datagrid.ready', ()=>{
            var clases = [{name:'linea-entregar', text: 'Entregado'}];
            //console.log('Entra en el listener');

            addlisteners({data:{claseslinea: clases}});
            // //console.log({data: {claselinea:clase}});
            // $('.page-link').click({claselinea: clase}, addlisteners);
            ////console.log($('.gc-container'));

            var mutationObserver = new MutationObserver(function(mutations){
                mutations.forEach(function(mutation){
                    // console.log(mutation);
                    addlisteners({data:{claseslinea: clases}});
                });
            });
            //mutationObserver.observe(document.getElementsByClassName('grocery-crud-table')[0],{
            //console.log($(".grocery-crud-table > tbody")[0]);
            mutationObserver.observe($(".grocery-crud-table > tbody")[0],{
                childList: true,
                subtree: true
            });
        });

        $('.gc-container').groceryCrud();

        function addlisteners(clases){
            // console.log('añadiendo listeners ');
            //console.log(clases.data.claseslinea);
            clases.data.claseslinea.forEach(c =>
                // console.log(c)
                $("[class*='" + c.name + "']").each(function(i){
                    // console.log($(this));
                    var clase = $(this).attr('class');
                    var index = clase.indexOf(c.name);
                    var numero = clase.substring(index+c.name.length, clase.length);
                    //console.log(numero);
                    $(this).off('click');
                    $(this).click({idnumero: numero, texto: c.text},function(e) {
                        //console.log(e);d
                        $("#" + e.data.idnumero).html(e.data.texto);
                    });
                })
            );
        }
    });
    </script>
</body>

</html>