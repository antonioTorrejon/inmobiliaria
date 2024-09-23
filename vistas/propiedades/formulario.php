<fieldset>
    <legend>Información general</legend>

        <label for="titulo">Título: </label>
        <input type="text" id="titulo" name="propiedad[titulo]" placeholder="Título propiedad" value="<?php echo sanar($propiedad->titulo); ?>">

        <label for="precio">Precio: </label>
        <input type="number" id="precio" name="propiedad[precio]" placeholder="Precio propiedad" value="<?php echo sanar($propiedad->precio); ?>">

        <label for="imagen">Imagen: </label>
        <input type="file" id="imagen" accept="image/jpeg, image/png" name="propiedad[imagen]">

        <?php if($propiedad->imagen): ?>
            <img src="/imagenes/<?php echo $propiedad->imagen; ?>" class="imagen-small">
        <?php endif; ?>

        <label for="descripcion">Descripcion: </label>
        <textarea id="descripcion" name="propiedad[descripcion]"> <?php echo sanar($propiedad->descripcion); ?></textarea>
</fieldset>

<fieldset>
    <legend>Información propiedad</legend>

        <label for="habitaciones">Habitaciones: </label>
        <input type="number" id="habitaciones" name="propiedad[habitaciones]" placeholder="Ej: 3" min="1" max="20" value="<?php echo sanar($propiedad->habitaciones); ?>">

        <label for="wc">Baños: </label>
        <input type="number" id="wc" name="propiedad[wc]" placeholder="Ej: 3" min="1" max="20" value="<?php echo sanar($propiedad->wc); ?>">

        <label for="parking">Parking: </label>
        <input type="number" id="parking" name="propiedad[parking]" placeholder="Ej: 3" min="1" max="20" value="<?php echo sanar($propiedad->parking); ?>">
</fieldset>

<fieldset>
     <legend>Vendedor</legend>

     <label for="vendedor">Vendedor</label>
     <select name="propiedad[vendedores_id]" id="vendedor">
        <option selected value="">-- Seleccione --</option>
        <?php foreach ($vendedores as $vendedor) { ?>
            <option 
                <?php echo $propiedad->vendedores_id == $vendedor->id ? 'selected': ''; ?>
                value="<?php echo sanar($vendedor->id); ?>"> 
                <?php echo sanar($vendedor->nombre) . " " . sanar($vendedor->apellido); ?>
            </option>
        <?php } ?>

</fieldset>