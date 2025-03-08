<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Config\Config;
use App\Core\Security\Authentication\Authentication;
use App\Core\View\Text;
use App\Modules\Bank\TransactionCategoriesMapper;
use App\Modules\Bank\TransactionContactMapper;
use App\Modules\Bank\TransactionImportMapper;
use App\Modules\Bank\TransactionMapper;
use League\Csv\Reader;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Bank", name="bank")
 */
class BankController extends AbstractController
{
    public function __construct()
    {
        Authentication::checkAuthentication();
    }

    /**
     * @Route("/Imports", name="imports")
     */
    public function imports(): Response
    {
        $imports = TransactionImportMapper::getAll();
        return $this->render('Pages/Bank/Imports', [ 'title' => 'Imports', 'imports' => $imports ]);
    }

    /**
     * @Route("/Contacts", name="contacts")
     */
    public function contacts(): Response
    {
        $currentTransactionContacts = TransactionContactMapper::getAll();
        $transactionsContacts = TransactionMapper::getAccountNames();

        return $this->render('Pages/Bank/Contacts', [
            'title'                      => Text::get('TITLE_TRANSACTION_CONTACTS'),
            'currentTransactionContacts' => $currentTransactionContacts,
            'transactionsContacts'       => $transactionsContacts
        ]);
    }

    /**
     * @Route("/Categories", name="categories")
     */
    public function categories(): Response
    {
        $year = (int) $this->request->get('year');
        $categories = TransactionCategoriesMapper::getCategoriesWithYear();
        $categoriesnotused = TransactionCategoriesMapper::getUnusedCategories();

        return $this->render('Pages/Bank/Categories', [
            'title'             => 'Categories',
            'categories'        => $categories,
            'categoriesnotused' => $categoriesnotused,
            'year'              => $year
        ]);
    }

    /**
     * @Route("/Transactions", name="transactions")
     */
    public function transactions(): Response
    {
        $id = (int) $this->request->get('importid');
        if (!empty($id)) {
            $transactions = TransactionMapper::getByImportId($id);
        } else {
            $transactions = TransactionMapper::getAll();
        }
        $categories = TransactionCategoriesMapper::getCategories();


        return $this->render('Pages/Bank/Transactions', [
            'title'      => 'Transactions',
            'imports'    => $transactions,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/Process", name="process")
     */
    public function process(): Response
    {
        $id = (int) $this->request->get('id');
        $import = TransactionImportMapper::getById($id);
        if ($import->processed === 0) {
            $reader = Reader::createFromPath(Config::get('PATH_BANK_UPLOADS') . $import->path);
            $reader->setHeaderOffset(0); //set the CSV header offset
            $reader->addStreamFilter('convert.iconv.ISO-8859-15/UTF-8');
            $records = $reader->getRecords([
                'IBAN/BBAN',
                'Munt',
                'BIC',
                'Volgnr',
                'Datum',
                'Rentedatum',
                'Bedrag',
                'Saldo na trn',
                'Tegenrekening IBAN/BBAN',
                'Naam tegenpartij',
                'Naam uiteindelijke partij',
                'Naam initiërende partij',
                'BIC tegenpartij',
                'Code',
                'Batch ID',
                'Transactiereferentie',
                'Machtigingskenmerk',
                'Incassant ID',
                'Betalingskenmerk',
                'Omschrijving-1',
                'Omschrijving-2',
                'Omschrijving-3',
                'Reden retour',
                'Oorspr bedrag',
                'Oorspr munt',
                'Koers'
            ]);
            foreach ($records as $offset => $record) {
                TransactionMapper::create($record, $id);
            }
            TransactionImportMapper::setProcessed($id);
        }
        $this->addFlash('success', Text::get('FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL'));
        return $this->redirectToRoute('bankview');
    }

    /**
     * @Route("/View", name="view")
     */
    public function view(): Response
    {
        $adapter = new LocalFilesystemAdapter(DIR_ROOT);
        $filesystem = new Filesystem($adapter);
        $files = $filesystem->listContents(Config::get('PATH_BANK_UPLOADS'));
        $array = [];
        foreach ($files as $file) {
            $array[] = $file->path();
        }
        $reader = Reader::createFromPath($array[0]);
        $reader->setHeaderOffset(0); //set the CSV header offset
        $reader->addStreamFilter('convert.iconv.ISO-8859-15/UTF-8');
        $records = $reader->getRecords([
            'IBAN/BBAN',
            'Munt',
            'BIC',
            'Volgnr',
            'Datum',
            'Rentedatum',
            'Bedrag',
            'Saldo na trn',
            'Tegenrekening IBAN/BBAN',
            'Naam tegenpartij',
            'Naam uiteindelijke partij',
            'Naam initiërende partij',
            'BIC tegenpartij',
            'Code',
            'Batch ID',
            'Transactiereferentie',
            'Machtigingskenmerk',
            'Incassant ID',
            'Betalingskenmerk',
            'Omschrijving-1',
            'Omschrijving-2',
            'Omschrijving-3',
            'Reden retour',
            'Oorspr bedrag',
            'Oorspr munt',
            'Koers'
        ]);
        //        foreach ($records as $offset => $record) {
        //            TransactionMapper::create($record);
        //        }
        //        die;
        return $this->render('Pages/Bank/View', [
            'title'   => 'Bankboek',
            'records' => $records
        ]);
    }

    /**
     * @Route("/Import", name="import")
     */
    public function import(): Response
    {
        return $this->render('Pages/Bank/Import');
    }

    public function setCategory(Request $request): Response
    {
        $transactionIDs = (array) $this->request->get('transactionIDs');
        $category = (int) $this->request->get('categoryId');
        //        echo 'error. transactionids:';
        //        var_dump($transactionIDs);
        //        echo 'category:';
        //        var_dump($category);
        //        die;
        $errorcount = 0;
        if (!empty($transactionIDs) || !empty($category)) {
            foreach ($transactionIDs as $transactionId) {
                if (TransactionMapper::setCategory((int) $transactionId, $category)) {
                    $this->addFlash('success', 'success');
                } else {
                    $this->addFlash('danger', 'failed');
                    $errorcount = +1;
                }
            }
        }
        if ($errorcount > 0) {
            return $this->redirectToRoute('error');
        }
        return $this->redirectToRoute('bank');
    }

    public function uploadCsv(Request $request): Response
    {
        $file = Request::files('csv_file');
        if ($this->processUploadedCsv($file)) {
            $this->addFlash('success', Text::get('FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL'));
            return $this->redirectToRoute('bankimports');
        }
        return $this->redirectToRoute('bankimport');
    }

    public function processUploadedCsv($file = null): bool
    {
        $adapter = new LocalFilesystemAdapter(DIR_ROOT);
        $filesystem = new Filesystem($adapter);
        $stream = fopen($file['tmp_name'], 'rb+');
        TransactionImportMapper::create($file['name']);
        try {
            $filesystem->writeStream($file['name'], $stream);
        } catch (FilesystemException $e) {
        } finally {
            //        if (is_resource($stream)) {}
            return fclose($stream);
        }
    }

    //    public static function mapTransactionContact()
    //    {
    //        $contactId = $this->request->get('contactId');
    //        $iban = $this->request->get('iban');
    //        $affected = TransactionMapper::updateTransactionContactId($contactId, $iban);
    //        $this->addFlash('success','transactions updated:' . $affected);
    //        Redirect::to('Bank/Transactions');
    //    }

    public function createTransactionContact(): Response
    {
        $name = $this->request->get('name');
        $iban = $this->request->get('iban');
        TransactionContactMapper::create($iban, $name);
        $createdId = TransactionContactMapper::lastInsertedId();
        $affected = TransactionMapper::updateTransactionContactId($createdId, $iban, $name);
        $this->addFlash('success', 'transactions updated:' . $affected);
        return $this->redirectToRoute('banktransactions');
    }

    public function newCategory(): Response
    {
        $name = (string) $this->request->get('name');
        $code = (int) $this->request->get('code');
        if (!empty($name) && !empty($code) && TransactionCategoriesMapper::new($name, $code)) {
            $this->addFlash('success', 'Category added:');
        } else {
            $this->addFlash('danger', 'Failed to add category:');
        }
        return $this->redirectToRoute('bankcategories');
    }

    public function createalltransactioncontacts(): Response
    {
        $id = $this->request->get('importid');
        $transactions = TransactionMapper::getToProcess($id);
        foreach ($transactions as $transaction) {
            if (!empty($transaction['Naam tegenpartij'])) {
                $existingId = TransactionContactMapper::doesExist($transaction['Tegenrekening IBAN/BBAN'], $transaction['Naam tegenpartij']);
                if ($existingId !== null) {
                    $createdId = $existingId;
                } else {
                    TransactionContactMapper::create($transaction['Tegenrekening IBAN/BBAN'], $transaction['Naam tegenpartij']);
                    $createdId = TransactionContactMapper::lastInsertedId();
                }
                $affected = TransactionMapper::updateTransactionContactId($createdId, $transaction['Tegenrekening IBAN/BBAN'], $transaction['Naam tegenpartij']);
                $this->addFlash('success', 'transactions updated:' . $affected);
            }
        }
        return $this->redirectToRoute('banktransactions');
    }
}
