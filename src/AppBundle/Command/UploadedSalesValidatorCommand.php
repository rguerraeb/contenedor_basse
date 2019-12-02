<?php
namespace AppBundle\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\Entity\SaleStaff;
use AppBundle\Entity\AccruedPointDetails;

class UploadedSalesValidatorCommand extends ContainerAwareCommand
{

    protected function configure ()
    {
        $this->
        // the name of the command (the part after "app/console")
        setName('staff:validate-uploaded-sales')->

        // the short description shown while running "php app/console list"
        setDescription(
                'Valida las facturas cargadas en el sistema y reparte puntos para vendedores y gerentes de tienda');

        // the full command description shown when running the command with
        // the "--help" option
        // ->setHelp("This command will take the staff citizenId, encript it and
        // save it as password")
        
    }

    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
                array(
                        "",
                        "Iniciando Proceso... " . date('Y-m-d h:i:s'),
                        '=======================================',
                        "",
                        'Obteniendo ventas cargadas en el sistema..',
                        ''
                ));

        // Getting staffs
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $si = $em->getRepository("AppBundle:SmsIncoming")->findBy(
                array(
                        "alreadyParsed" => 0
                ));
        
        if (count($si) > 0) {
            $output->writeln(array(
                    "Se encontraron " . count($si) . " facturas pendientes, iniciando recorrido..."
            ));
            $jobPositionGerente = 1;
            $jobPositionVendedor = 2;
            // obtener reward criteria para gerentes
            $rcGerente = $em->getRepository("AppBundle:RewardCriteria")->findOneBy(array("filterGroup" => 5, "jobPosition" => $jobPositionGerente));
            // obtener reward criteria para ventas
            $rcVentas = $em->getRepository("AppBundle:RewardCriteria")->findOneBy(array("filterGroup" => 5, "jobPosition" => $jobPositionVendedor));
            foreach ($si as $item) {
                // buscar sale
                $sale = $em->getRepository("AppBundle:Sale")->findOneBy(array("skuCode" => $item->getSmsString()));
                if ($sale) {
                    // obtener gerente asociado
                    $gerente =  $em->getRepository("AppBundle:Staff")->getStaffSeller($sale->getpointOfSale()->getPointOfSaleId(), $jobPositionGerente);
                    $vendedor = $em->getRepository("AppBundle:Staff")->getStaffSeller($sale->getpointOfSale()->getPointOfSaleId(), $jobPositionVendedor);
                    
                    //dar puntos al gerente 
                    if (isset($gerente["staff_id"])) {
                        $puntosGerente = round($sale->getPrice() * $rcGerente->getMathematicalOperator() / 100, 2);
                        $ssGerente = new SaleStaff();
                        $ssGerente->setCreatedAt(new \DateTime());
                        $ssGerente->setCreatedBy(1);
                        $ssGerente->setIsCancelled(0);
                        $ssGerente->setPoints($puntosGerente);
                        $ssGerente->setSale($sale);
                        $ssGerente->setStaff($em->getReference(
                                'AppBundle\Entity\Staff',
                                $gerente["staff_id"]));
                        $ssGerente->setWasSeller(0);
                        $em->persist($ssGerente);
                        
                        // crear accrued points
                        $acg = new AccruedPointDetails();
                        $acg->setAccruedPoints($puntosGerente);
                        $acg->setAvailablePoints($puntosGerente);
                        $acg->setCreatedAt(new \DateTime());
                        $acg->setPointType($em->getReference(
                                'AppBundle\Entity\PointType',5));
                        $acg->setSale($sale);
                        $acg->setStaff($em->getReference(
                                'AppBundle\Entity\Staff',
                                $gerente["staff_id"]));
                        
                        $item->setParseResult("Puntos otorgados gerente: " . $puntosGerente);
                        
                        $em->persist($acg);
                    } else {
                        $item->setParseResult("No se encontro un gerente asociado a este punto de venta. ");
                    }
                    
                    //dar puntos al vendedor
                    if (isset($vendedor["staff_id"])) {
                        $puntosVendedor = round($sale->getPrice() * $rcVentas->getMathematicalOperator() / 100, 2);
                        $ssVentas = new SaleStaff();
                        $ssVentas->setCreatedAt(new \DateTime());
                        $ssVentas->setCreatedBy(1);
                        $ssVentas->setIsCancelled(0);
                        $ssVentas->setPoints($puntosVendedor);
                        $ssVentas->setSale($sale);
                        $ssVentas->setStaff($em->getReference(
                                'AppBundle\Entity\Staff',
                                $vendedor["staff_id"]));
                        $ssVentas->setWasSeller(1);
                        $em->persist($ssVentas);
                        
                        // crear accrued points
                        $acv = new AccruedPointDetails();
                        $acv->setAccruedPoints($puntosVendedor);
                        $acv->setAvailablePoints($puntosVendedor);
                        $acv->setCreatedAt(new \DateTime());
                        $acv->setPointType($em->getReference(
                                'AppBundle\Entity\PointType', 5));
                        $acv->setSale($sale);
                        $acv->setStaff($em->getReference(
                                'AppBundle\Entity\Staff',
                                $vendedor["staff_id"]));
                        
                        $item->setParseResult($item->getParseResult() . " - Puntos otorgados ventas: " . $puntosVendedor);
                        
                        $em->persist($acv);
                        
                    } else {
                        $item->setParseResult($item->getParseResult() . " - No se encontro un gerente asociado a este punto de venta. ");
                    }
                    
                    
                    $item->setAlreadyParsed(1);
                } else {
                    $output->writeln(array(
                            "NO se encontro venta para el registro con sms_string " . $item->getSmsString() . ", el registro sera marcado como procesado.",
                            ""
                    ));
                    $item->setAlreadyParsed(1);
                    $item->setParseResult($item->getParseResult() . " - NO se enctro venta para el registro con este sms_string, el registro sera marcado como procesado.");
                    $em->persist($item);
                }
                
            }
            
            // guardar todos los datos
            $output->writeln(array(
                    "Guardando datos ...",
                    "",
            ));
            $em->flush();
            
        } else {
            $output->writeln(array(
                    "NO se encontraron facturas pendientes para procesar.",
                    ""
            ));
        }
        
        

        //
        $output->writeln(array(
                "Proceso finalizado... " . date('Y-m-d h:i:s')                
        ));
    }
}