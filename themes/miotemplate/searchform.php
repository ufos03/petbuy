<!--
<form class="d-flex w-75 mx-auto my-2 my-lg-0 align-self-end" action="?php echo home_url('/');?>">

<input class="form-control me-2" type="search" id="ricerca" placeholder="Ricerca i tuoi prodotti" aria-label="Cerca" value="?php echo get_search_query(); ?>" name="s">
<i id="lente" class="fa fa-search"></i>

</form>
-->

      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-md-8 col-lg-11">
            <form class="d-flex my-2 my-lg-0" action="<?php echo home_url('/');?>">
              <input class="form-control edit-form-header" type="search" id="ricerca" placeholder="Ricerca i tuoi prodotti" aria-label="Cerca" value="<?php echo get_search_query(); ?>" name="s">
              <i id="lente" class="fa fa-search"></i>
            </form>
          </div>
        </div>
      </div>
