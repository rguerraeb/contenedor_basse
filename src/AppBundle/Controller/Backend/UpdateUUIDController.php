<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class UpdateUUIDController extends Controller
{
	
	/**
	 * @Route("/backend/update-uuid", name="backend_update_uuid")
	 */

	public function updateAction(Request $request)
	{

    $em = $this->getDoctrine()->getManager();

		$uuidTaxIdentifiers =
			array('965b6c346f58aa0b'=>'9479091-4',
			      'be86a61eeb855d4f'=>'9504856-1',
			      '1f8c5c0193bc362b'=>'8167539-9',
			      '664b6c0dfb055746'=>'8902979-8',
			      'e430010154f60cb8'=>'8902979-8',
			      'fbf93cb2e511891d'=>'8902979-8',
			      '378c9f4d55afd0ea'=>'7619386-1',
			      'ddb7eb57f980bdeb'=>'665427-4',
			      '4977a555b6a516c5'=>'665427-5',
			      'a9a4da5637f45d91'=>'665427-6',
			      'd54e58e5be78ea6b'=>'9210812-1',
			      '363562f4c0c8e9d3'=>'9210812-2',
			      '38265a598b8b8b5e17'=>'3729310-9',
			      'f1b1e44085420655'=>'3729310-10',
			      '89095283eca69343'=>'3729310-11',
			      'a48d872e0370c7b5'=>'7621566-0',
			      'c88e9232cc0f1f8c'=>'7566730-4',
			      '6b824d47d3cd3fe9'=>'3964327-1',
			      'ee50aabd2586b626'=>'9037833-4',
			      'c9853c20b4c0e0e3'=>'8233213-4',
			      '7cff9a1b81d980b2'=>'6749135-9',
			      'e0f402509be90073'=>'6749135-9',
			);

			$uuidStates = array(
				'965b6c346f58aa0b'=>'SUCHITEPEQUEZ',
				'be86a61eeb855d4f'=>'RETALHULEU',
				'1f8c5c0193bc362b'=>'SUCHITEPEQUEZ',
				'664b6c0dfb055746'=>'SUCHITEPEQUEZ',
				'e430010154f60cb8'=>'SUCHITEPEQUEZ',
				'fbf93cb2e511891d'=>'SUCHITEPEQUEZ',
				'378c9f4d55afd0ea'=>'SUCHITEPEQUEZ',
				'ddb7eb57f980bdeb'=>'SUCHITEPEQUEZ',
				'4977a555b6a516c5'=>'QUETZALTENANGO',
				'a9a4da5637f45d91'=>'RETALHULEU',
				'd54e58e5be78ea6b'=>'SUCHITEPEQUEZ',
				'363562f4c0c8e9d3'=>'SUCHITEPEQUEZ',
				'38265a598b8b8b5e17'=>'QUETZALTENANGO',
				'f1b1e44085420655'=>'QUETZALTENANGO',
				'89095283eca69343'=>'RETALHULEU',
				'a48d872e0370c7b5'=>'RETALHULEU',
				'c88e9232cc0f1f8c'=>'RETALHULEU',
				'6b824d47d3cd3fe9'=>'RETALHULEU',
				'ee50aabd2586b626'=>'RETALHULEU',
				'c9853c20b4c0e0e3'=>'QUETZALTENANGO',
				'7cff9a1b81d980b2'=>'SAN MARCOS',
				'e0f402509be90073'=>'SAN MARCOS',
			);

			$uuidCities = array(
				'965b6c346f58aa0b'=>'MAZATENANGO',
				'be86a61eeb855d4f'=>'RETALHULEU',
				'1f8c5c0193bc362b'=>'MAZATENANGO',
				'664b6c0dfb055746'=>'PATULUL',
				'e430010154f60cb8'=>'SAMAYAC',
				'fbf93cb2e511891d'=>'SANTO TOMAS LA UNION',
				'378c9f4d55afd0ea'=>'MAZATENANGO',
				'ddb7eb57f980bdeb'=>'MAZATENANGO',
				'4977a555b6a516c5'=>'COATEPEQUE',
				'a9a4da5637f45d91'=>'SAN SEBASTIAN',
				'd54e58e5be78ea6b'=>'MAZATENANGO',
				'363562f4c0c8e9d3'=>'MAZATENANGO',
				'38265a598b8b8b5e17'=>'COLOMBA',
				'f1b1e44085420655'=>'COATEPEQUE',
				'89095283eca69343'=>'RETALHULEU',
				'a48d872e0370c7b5'=>'RETALHULEU',
				'c88e9232cc0f1f8c'=>'RETALHULEU',
				'6b824d47d3cd3fe9'=>'SAN FELIPE',
				'ee50aabd2586b626'=>'RETALHULEU',
				'c9853c20b4c0e0e3'=>'COATEPEQUE',
				'7cff9a1b81d980b2'=>'PAJAPITA',
				'e0f402509be90073'=>'PAJAPITA',
			);

			$uuidAddress = array(
				'965b6c346f58aa0b'=>'1ERA AVENIDA 4-37 ZONA 02 COLONIA OBREGON',
				'be86a61eeb855d4f'=>'11 CALLE 8-84 BARRIO SAN NICOLAS ',
				'1f8c5c0193bc362b'=>'KILOMETRO 156 CARRETERA AL PACIFICO, COLONIA EL COMPROMISO ',
				'664b6c0dfb055746'=>'1 AVENIDA Y CALLE DEL RASTRO ZONA 1',
				'e430010154f60cb8'=>'5 CALLE 9 - 14 ZONA 1 CANTON CONCEPCION ',
				'fbf93cb2e511891d'=>'4 AVENIDA 0-95 ZONA 1',
				'378c9f4d55afd0ea'=>'2 CALLE 4-25, CANTON SANTA CRISTINA',
				'ddb7eb57f980bdeb'=>'KILOMETRO  163 CIRCUMVALACIÓN EL TRIANGULO',
				'4977a555b6a516c5'=>'FINCA LA CONCEPCIÓN 2 AVENIDA. 11-58 ZONA 3 BARRIO SAN FRANCISCO',
				'a9a4da5637f45d91'=>'KILOMETRO 181 CARRETERA RETORNO CANTÓN SAMALÁ',
				'd54e58e5be78ea6b'=>'1 av. 5-00 zona 2 Colonia Obregón, Mazatenango, Such.',
				'363562f4c0c8e9d3'=>'Colonia Bilbao, circunvalacion Mazatenango',
				'38265a598b8b8b5e17'=>'KILOMETRO  212.5 CARRETERA INTERAMERICANA',
				'f1b1e44085420655'=>'Interior parqueo de buses de parrilla',
				'89095283eca69343'=>'KM 183 CA-2 entrada condominio Premier, frente a bodegas de cerveceria Gallo',
				'a48d872e0370c7b5'=>'3 AVENIDA. 3-92  zona 4',
				'c88e9232cc0f1f8c'=>'5 AVENIDA. 9-93 ZONA 1',
				'6b824d47d3cd3fe9'=>'3 AVENIDA Y 6 CALLE 4-94 ZONA 1',
				'ee50aabd2586b626'=>'BOULEVARD CENTENARIO SANTA RITA II ',
				'c9853c20b4c0e0e3'=>'AVENIDA 20 DE OCTUBRE 4-04 ',
				'7cff9a1b81d980b2'=>'CARRETERA A TECÚN UMÁN KILÓMETRO 240',
				'e0f402509be90073'=>'CARRETERA A TECÚN UMÁN KILÓMETRO 244',

			);

		try {

			foreach ( $uuidTaxIdentifiers as $taxIdentifier => $value ) {

				$repo = $em->getRepository( 'AppBundle:PointOfSale' );

				$query = $repo->createQueryBuilder( 'pos' );
				$query->join( 'pos.state', 'st' );
				$query->join( 'pos.city', 'c' )
				      ->where( 'pos.taxIdentifier = :taxIdentifier' )
				      ->andWhere( 'st.name = :stateName' )
				      ->andWhere( 'c.name = :cityName' )
				      ->andWhere( 'pos.address1 = :address1' )
				      ->setParameter( 'taxIdentifier', $value )
				      ->setParameter( 'stateName', $uuidStates[ $taxIdentifier ] )
				      ->setParameter( 'cityName', $uuidCities[ $taxIdentifier ] )
				      ->setParameter( 'address1', $uuidAddress[ $taxIdentifier ] );

				$items = $query->getQuery()->getResult();
				foreach ( $items as $item ) {
					$item->setDeviceUUID( $taxIdentifier );
					$em->persist( $item );
				}

			}
			$em->flush();
		} catch (\Exception $ex) {
			return new JsonResponse(array(
				'status' => 500,
				'message' => 'Error al tratar de actualizar los uuids'
			));
		}
		return new JsonResponse(array(
			'status' => 200,
			'message' => 'Los datos han sido actualizados!'
		));

	}
}
