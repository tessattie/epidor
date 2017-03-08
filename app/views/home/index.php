<?php include(ROOT_DIRECTORY.'/app/views/header.php'); ?>
<div class="container-fluid">
	<div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li>EPI D'OR</li>
          <li>Manuel Utilisateur
          </li>
      </ul>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="container breadcrumb">
        <div id="utilisateurs">
          <h1>Gestion des utilisateurs</h1>
          <p class="manualparagraph">
            Afin de gérer les droits d'accès à l'application vous pouvez créer autant de profils utilisateurs nécessaires.
            Pour cela, il faut choisir l'onglet "Utilisateurs"  qui se trouve dans le menu principal : <br><br>
            <img src="<?= DIRECTORY_NAME ?>/img/aide/one.png" class="usermanual"><br><br>
            Cette page regroupe dans un tableau l'ensemble des utilisateurs que vous avez déjà créé ainsi que le formulaire de création 
            d'un nouvel utilisateur. 
            Lors de la création d'un nouvel utilisateur, il faut renseigner les champs suivants :
            <ul class="manualparagraph">
              <li><strong>Nom</strong></li>
              <li><strong>Prénom</strong></li>
              <li><strong>Nom d'utilisateur</strong></li>
              <li><strong>Statut</strong> : indique si ce compte utilisateur est actif ou pas. S'il n'est pas actif, l'utilisateur 
                ne pourra s'authentifier</li>
              <li><strong>Accès</strong> : gère les droits d'un utilisateur. Il existe deux rôles : 
                <ul>
                  <li>Super-admin : celui qui crée les clients, les produits et active / désactive n'importe quel élément</li>
                  <li>Admin : celui qui effectue les transactions</li>
                </ul></li>
            </ul><br><br>
            <img src="<?= DIRECTORY_NAME ?>/img/aide/two.png" class="usermanual"><br><br>
          </p>
          <p class="manualparagraph">
            Il est possible d'éditer le statut ou l'accès d'un utilisateur à n'importe quel moment en double-cliquant sur le champ dans le tableau
            puis en indiquant la valeur du nouveau champ
          </p>
        </div>
        <div id="clients">
          <h1>Gestion des clients</h1>
          <p class="manualparagraph">
            Afin de sauvegarder les transactions d'un client, il faut d'abord créer un compte pour celui-ci. Pour cela, il faut choisir 
            l'onglet "Clients" dans le menu principal. Cette page regroupe l'ensemble des informations de tous les clients qui ont été crées. 
            Seul le Super-admin peut créer un nouveau client. 
            Lors de la création d'un nouveau client, il faut renseigner les champs suivants :
            <ul class="manualparagraph">
              <li><strong>Nom</strong></li>
              <li><strong>Prénom</strong></li>
              <li><strong>NIF</strong> (composé de huit chiffres)</li>
              <li><strong>Téléphone</strong> : Ne pas indiquer +509. Il est ajouté automatiquement</li>
              <li><strong>Plafond</strong> : qui représente la limite du compte, c'est à dire la quantité de gourdes qu'il 
                peut avoir au maximum sur son compte</li>
              <li>Statut : si son compte est actif ou pas. S'il ne l'est pas, aucune transaction ou achat ne pourra être effectué sur ce compte</li>
            </ul><br>
          </p>
          <p class="manualparagraph">Tous les champs sont éditables en effectuant un double-click dans le tableau puis en indiquant la nouvelle valeur.</p>
        </div>
        <div id="produits">
          <h1>Gestion des produits</h1>
          <h3>Catégories</h3>
          <p class="manualparagraph">
            Afin de mieux classer un produit, il faut d'abord choisir la catégorie dans laquelle il se trouve. La première
            étape consiste donc a créer les catégories de produits. Soit les familles de produits différentes. Cette action 
            peut se faire en choisissant l'onglet "Produits" dans le menu principal. <br><br>
            Les champs à renseigner sont les suivants : 
            <ul class="manualparagraph">
              <li><strong>Nom</strong> de la catégorie</li>
              <li><strong>Statut</strong> : qui indique si cette catégorie de produits est active ou pas. Si une catégorie est inactive, 
                vous ne pourrez pas voir ses produits dans le catalogue OU lui attribuer un nouveau produit</li>
            </ul>
            
          </p>
          <p class="manualparagraph">
            Il est possible d'éditer le statut d'une catégorie en double-cliquant sur le champ correspondant dans le tableau. Vous pouvez rendre 
            une catégorie active ou inactive à n'importe quel moment. Seul le Super-admin peut changer le statut d'une catégorie.
          </p>
          <img src="<?= DIRECTORY_NAME ?>/img/aide/three.png" class="usermanual"><br><br>
          <h3>Produits</h3>
          <p class="manualparagraph">
            La création et l'édition d'un produit se fait dans la même page que celle des catégories. Seul le Super-admin peut accéder à cette page. 
            Les champs à renseigner lors de la création d'un produit sont les suivants : 
            <ul class="manualparagraph">
              <li><strong>Nom</strong></li>
              <li><strong>Catégorie</strong></li>
              <li><strong>Prix</strong></li>
              <li><strong>Statut</strong> : Si un produit est inactif, il n'apparaitra pas dans le catalogue de produits dans la
                page achats</li>
            </ul>
          </p>
           <p class="manualparagraph">
            Il est possible d'éditer un produit en double-cliquant sur le champ en question. Les champs éditables sont le prix, 
            la catégorie et le statut. Il n'est pas conseillé de supprimer un produit car celui-ci peut être associé à plusieurs achats
            et peut donc générer des erreurs au niveau des transactions.
           </p>
        </div>
        <div id="achats">
          <h1>Nouvel achat</h1>
          <p class="manualparagraph">
            Afin d'enregistrer un nouvel achat, il faut choisir l'onglet "Nouvelle fiche" dans le menu principal. 
          </p>
          <img src="<?= DIRECTORY_NAME ?>/img/aide/four.png" class="usermanual"><br><br>
          <p class="manualparagraph">
            La première étape est de choisir les produits dans le catalogue de produits entouré en rouge. Un produit 
            s'ajoute automatiquement à la fiche lorque vous cliquez sur celui-ci. En cliquant plusieurs fois sur un produit, 
            vous incrémentez sa quantité de 1. <br><br> Vous pouvez manuellement saisir la quantité en utilisant les touches 
            entourées en vert. Pour éditer la quantité d'un produit, cliquez sur la case "Qté" du produit associé dans le tableau, 
            saisissez les chiffres puis validez en appuyant sur le bouton vert du clavier de chiffres. 
            Afin de valider une fiche, appuyez sur "Valider fiche". 
            Si vous ajoutez un produit par accident, il faut recharger la page et recommencer. Il n'est pas possible de supprimer 
            un produit de la fiche en cours. <br>
            Vous pouvez filtrer les produits pour rendre la recherche plus facile en utilisant les filtres entourés en orange.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
      
<?php 
if($_SESSION["role"] == "SA")
{
  include(ROOT_DIRECTORY.'/app/views/footer.php'); 
}
else
{
  include(ROOT_DIRECTORY.'/app/views/footer_two.php'); 
}
?>
