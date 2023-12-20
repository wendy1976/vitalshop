<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    private $entityManager;

    // Injection du gestionnaire d'entités dans le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/afficher-produits', name: 'afficher_produits')]
    public function afficherProduits(): Response
    {
        $codesBarres = ['20023775', '3166359678103', '3175681210981', '3302741843104', '3175681072763', '3248830692287', '3266140060176', '3041091339911', '3041091339645', '3021690029093', '3240931545356',
            '3322680010405', '3242272371052', '3700679600194', '3038359003219', '3083681070491', '3038350208613', '3487400004390', '3270160891382', '3270160890163', '8720182108937', '3289943400611', '7613039972083', '3483463020247', '3289476000012', '3266140061548', '3175681061491', '3250391110483', '3252970009881', '3256220030458', '3256220071482', '3256224059752', '3266140060152', '7613035768154', '3256221976694', '3368955060294', '3560070818334', '3560070921942', '3564700653395', '3564709015484', '3021690010985', '3176580101349', '3248830227236', '3250391885244', '3256226758387', '3560070606900', '3256228125552', '3222473660568','3017620425035'];

        $produits = [];

        foreach ($codesBarres as $codeBarre) {
            $produits[] = $this->getProduitData($codeBarre);
        }

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    private function getProduitData(string $codeBarre): Produit
    {
        $urlApi = "https://world.openfoodfacts.org/api/v0/product/{$codeBarre}.json";
        $response = file_get_contents($urlApi);
        $data = json_decode($response, true);

        if (isset($data['product'])) {
            $idProduit = $data['product']['_id'];

            // Vérifier si le produit existe déjà dans la base de données
            $produitExist = $this->entityManager
            ->getRepository(Produit::class)
            ->findOneBy(['id_produit' => $idProduit]);
            if ($produitExist) {
                // Si le produit existe déjà, retourner le produit existant
                return $produitExist;
            }

            // Sinon, créer un nouveau produit
            $produit = new Produit();
            $produit->setIdProduit($idProduit);
            $produit->setReferenceProduit($data['product']['code']);
            $produit->setNomProduit($data['product']['product_name_fr'] ?? '');
            $produit->setDescriptionProduit($data['product']['ingredients_text_fr'] ?? '');
            $produit->setImageProduit($data['product']['image_url']);
            $produit->setMotsCles($data['product']['categories'] ?? '');
            $produit->setNoteNutriscore($data['product']['nutriscore_grade'] ?? '');
            $produit->setEnergie($data['product']['nutriments']['energy-kcal_100g'] ?? 0);

            // Persiste et sauvegarde le produit dans la base de données
            $this->entityManager->persist($produit);
            $this->entityManager->flush();

            return $produit;
        }

        return new Produit();
    }
}
 