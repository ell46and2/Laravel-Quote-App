<?php

namespace App\Http\Controllers;

use App\Author;
use App\Quote;
use App\AuthorLog;
use \Illuminate\Http\Request;
use App\Events\QuoteCreated;
use Illuminate\Support\Facades\Event;

class QuoteController extends Controller {

	public function getIndex($author = null) {

		// if an author has been sent in the URL find author, then get quotes by author.
		if (!is_null($author)) {
			$quote_author = Author::where('name', $author)->first();
			if ($quote_author) {
				$quotes = $quote_author->quotes()->orderBy('created_at', 'desc')->paginate(6);
			}
		} else {
			$quotes = Quote::orderBy('created_at', 'desc')->paginate(6);
		}

		

		return view('index', ['quotes' => $quotes]);
	}

	public function postQuote(Request $request) {

		$this->validate($request, [
			'author' => 'required|max:60|alpha',
			'email' => 'required|email',
			'quote' => 'required|max:500'
		]);

		$authorText = ucfirst($request['author']);
		$quoteText = $request['quote'];

		// search database for author, if it doesn't exist add the new author.
		$author = Author::where('name', $authorText)->first();
		if(!$author) {
			$author = new Author();
			$author->name = $authorText;
			$author->email = $request['email'];
			$author->save();
		}

		// Add quote to the DB
		$quote = new Quote();
		$quote->quote = $quoteText;
		// use the method in Providers/Author.php to set relationship
		$author->quotes()->save($quote);


		Event::fire(new QuoteCreated($author));


		return redirect()->route('index')->with([
			'success' => 'Quote saved!'
		]);
	}

	public function getDeleteQuote($quote_id) {
		$quote = Quote::find($quote_id);
		$author_deleted = false;

		// if this is the only quote from the author, delete author.
		if(count($quote->author->quotes) === 1) {
			$quote->author->delete();
			$author_deleted = true;
		}

		$quote->delete();

		$msg = $author_deleted ? 'Quote and Author deleted' : 'Quote deleted';
		return redirect()->route('index')->with(['success' => $msg]);

	}	

	public function getMailCallback($author_name) {

		$author_log = new AuthorLog();
		$author_log->author = $author_name;
		$author_log->save();

		return view('email.callback', ['author' => $author_name]);
	}
}