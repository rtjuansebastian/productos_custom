<?php $this->load->view("header");  ?>      
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form class="form-inline" action="<?=  base_url()?>index.php/index/exportar_excel_schneider" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <span>Importar desde excel: </span>
                </div>
                <div class="form-group">
                    <span class="btn btn-sm btn-success btn-file  glyphicon glyphicon-search">
                        <input type="file" name="xlsxfile" size="40" required=""/>
                    </span>
                </div>
                <input type="hidden" name="convert" />
                <div class="form-group">
                    <button type="submit" class="btn btn-success" name = "upload" value="subir"><span class="glyphicon glyphicon-open"></span></button>
                </div>
            </form>            
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            if(isset($alert)){
                echo $alert;
            }
            ?>
        </div>
    </div>
</div>
<?php $this->load->view("footer");  ?>     