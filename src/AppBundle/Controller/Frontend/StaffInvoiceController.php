<?php
namespace AppBundle\Controller\Frontend;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Helper\SessionHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Repository\EbClosion;
use AppBundle\Entity\Staff;
use Doctrine\DBAL\Types\JsonArrayType;
use AppBundle\Entity\InvoicePending;

class StaffInvoiceController extends Controller
{

    private $moduleId = 30;

    /**
     *
     * @Route("/staff/invoice", name="staff_invoice")
     */
    public function registerSaleAction (Request $request)
    {
        $this->get("session")->set("module_id", $this->moduleId);

        $invoices = $request->files->get("invoice");
        $em = $this->getDoctrine()->getEntityManager();

        if ($invoices) {
            // validate images
            foreach ($invoices as $file) {
                if ($file) {

                    $extension = $file->guessExtension();

                    if (! ($extension == 'jpg' || $extension == 'jpeg' ||
                            $extension == 'png' || $extension == 'JPG' ||
                            $extension == 'JPEG' || $extension == 'PNG')) {

                        $this->addFlash('error_message',
                                $this->getParameter('extension_error_invoice'));
                        return $this->redirectToRoute("staff_invoice");
                    }
                }
            }
            // upload images
            foreach ($invoices as $file) {
                if ($file) {

                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                    $file->move($this->getParameter('invoice_image'), $fileName);

                    $invoice = new InvoicePending();
                    $invoice->setCreatedAt(new \DateTime());
                    $invoice->setCreatedBy($this->getUser()
                        ->getStaffId());
                    $invoice->setInvoiceImage($fileName);
                    $invoice->setInvoiceStatus("PENDIENTE");
                    $invoice->setStaff($this->getUser());
                    $em->persist($invoice);
                }
            }
            $em->flush();
            $this->addFlash('alert_message',
                    sprintf($this->getParameter('exito_factura_pendiente'),
                            count($invoices)));
            return $this->redirectToRoute("staff_invoice");
        }

        $list = $em->getRepository("AppBundle:InvoicePending")->findBy(
                array(
                        "staff" => $this->getUser()
                ));

        // replace this example code with whatever you need
        return $this->render('@App/Frontend/Staff/register_sale.html.twig',
                array(
                        'staff' => $this->getUser(),
                        'url_entrance' => $this->getParameter('url_entrance'),
                        "list" => $list
                ));
    }
    
    /**
     *
     * @Route("/invoice-detail", name="staff_invoice_detail")
     */
    public function invoiceDetailAction (Request $request)
    {
        $invoiceNumber = $request->get("invoiceNumber", false);
        if ($invoiceNumber) {
            $sale = $this->getDoctrine()->getRepository("AppBundle:Sale")->findOneBy(array("invoiceNumber" => $invoiceNumber));
            if ($sale) {
                $ppd = $this->getDoctrine()->getRepository("AppBundle:PurchasedProductDetail")->findBy(array("sale" => $sale));
                return $this->render('@App/Frontend/Staff/purchased_product_list.html.twig',
                        array(
                                "list" => $ppd
                        ));
            } else {
                echo "No se encontro detalle para esta factura.";
            }
        }
        
        die;
    }


}
