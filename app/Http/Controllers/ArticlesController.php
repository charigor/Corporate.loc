<?php

namespace Corp\Http\Controllers;

use Illuminate\Http\Request;

use Corp\Repositories\PortfoliosRepository;
use Corp\Repositories\SidebarRepository;
use Corp\Repositories\ArticlesRepository;
use Corp\Repositories\CommentsRepository;
use Config;
use DB;

class ArticlesController extends SiteController
{
	public function __construct(PortfoliosRepository $p_rep,ArticlesRepository $a_rep,CommentsRepository $c_rep){
	    parent::__construct(new \Corp\Repositories\MenusRepository(new \Corp\Menu));

	    $this->bar = 'right';
	    $this->a_rep = $a_rep;
        $this->p_rep = $p_rep;
        $this->c_rep = $c_rep;
	    $this->template = env('THEME').'.articles';

	}
    //
    function index(){
    	$this->title = 'My site';
        $this->meta_desc = 'Описание';
        $this->keywords = 'Ключевые слова';
        $comments = $this->getComments();
        $portfolios = $this->getPortfolios();
        $this->contentRightBar=view(env('THEME').'.articlesBar')->with(['comments'=>$comments,'portfolios'=>$portfolios])->render();
        $articles = $this->getArticles();
        $content = view(env('THEME').'.articles_content')->with('articles',$articles)->render();
        $this->vars = array_add($this->vars,'content',$content);
        
    	return $this->renderOutput();
    }

     protected function getArticles($alias=false){
        $articles = $this->a_rep->get(['title','desc','alias','img','created_at','user_id','category_id'],FALSE,TRUE);
        //$articles->load('user','category','comment');
        return $articles;
    }
    protected function getComments(){
         $comments =$this->c_rep->get();
         return $comments;
    }
    protected function getPortfolios(){
        $portfolios = $this->p_rep->get();
        return $portfolios;
    }
}