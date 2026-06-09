<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Role implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        if ('role' == 'admin') {
            return redirect()->to(site_url('produk'));
        } else if ('role' == 'guest') {
            return redirect()->to(site_url('contact'));
        } else {
            return redirect()->to(site_url('login'));
        }
    }
    

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}