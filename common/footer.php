        </article>

        <div id="footer">
           <hr />
           <div class="footer">
              <div class="row2">
              <div class="span3-linda bar-linda about">
                <h3>About</h3>
                <ul>
                  <li><a href="/transcribe/about">About the project</a></li>
                  <li><a href="/transcribe/about#faq">FAQ</a></li>
                  <li><a href="/transcribe/about#tips">Transcription tips</a></li>
                  <li><a href="/transcribe/about#contact">Contact us</a></li>
                </ul>
                <h3>Contribute</h3>
                <ul>
				  <li><?php
				  //function to count completed items and provide that number on the page
                  function countFinished() {
					$usr = "USR";
					$pwd = "PWD";
					$db = "OMEKA_DB";
					$host = "HOST";

					$con = mysql_connect($host, $usr, $pwd);

					if (!$con) {
						die('Could not connect.');
					}

					mysql_select_db($db, $con);

					$result = mysql_query("SELECT COUNT( * ) FROM omeka_element_texts WHERE (text = 'Completed')") or die(mysql_error());

					while ($rows = mysql_fetch_array($result)) {
						$count = $rows[0];
						$total = (int)$count;
					}
					mysql_close($con);
					//once collections are fully transcribed we export the transcriptions and delete the items
					//the line below adds the total 'Completed' found currently in the database,
					//and items completed in the past that have since been deleted, currently 785
					$total_int = intval($total) + 785;
					//format and string out
					$total_str = number_format(strval($total_int));
					return $total_str;
				  }
				  echo countFinished();
				  ?> pages transcribed</li>
                  <li><a href="https://github.com/LibraryofVA/MakingHistory-transcribe-2.0" target="_blank">Get the code</a></li>
                </ul>
              </div>
              <div class="span3-linda bar-linda connect">
                <h3>Virginia Memory</h3>
                <div>Making History - Transcribe is a digital collection based project part of <a href="/" title="Virginia Memory">Virginia Memory</a>, the <a href="http://www.lva.virginia.gov" title="Library of Virginia" target="_blank">Library of Virginia</a>'s home for digital collections.</div>
                <br>
                <h3>Connect With Us</h3>
                <div id="connected">
			        <a title="Join us on Facebook" href="http://www.facebook.com/LibraryofVA"><img src="/img/hp_facebook.png"></a> <a title="Follow us on Twitter" href="http://twitter.com/LibraryofVA"><img src="/img/hp_twitter.png"></a> <a title="Check out our Flickr Photostream" href="http://www.flickr.com/photos/library_of_virginia/"><img src="/img/hp_flickr.png"></a> <a title="The Library of Virginia's Google Cultural Institute Home" href="https://www.google.com/culturalinstitute/collection/library-of-virginia"><img alt="Google Cultural Institute Icon" src="/img/hp_gci.png"></a> <a title="View our YouTube Channel" href="http://www.youtube.com/user/LibraryofVa"><img src="/img/hp_youtube.png"></a> <a target="_blank" title="Historypin Channel" href="http://www.historypin.com/channels/view/id/8307088/"><img alt="Historypin Channel" src="/img/hp_historypin.png"></a> <a target="_blank" title="Tumblr" href="http://libraryofva.tumblr.com/"><img alt="Tumblr" src="/img/hp_tumblr.png"></a> <a target="_blank" title="Pinterest" href="http://pinterest.com/libraryofva/"><img alt="Pinterest" src="/img/hp_pinterest.png"></a>  <a target="_blank" title="Instagram" href="http://instagram.com/libraryofva"><img alt="Instagram" src="/img/hp_instagram.png"></a>
		        </div>
              </div>
              <div class="span3-linda bar-linda leaderboard">
              	<?php
					$usr = "USR";
					$pwd = "PWD";
					$db = "WIKI_DB";
					$host = "HOST";

					$con = mysql_connect($host, $usr, $pwd);

					if (!$con) {
						die('Could not connect.');
					}
					mysql_select_db($db, $con);
				?>
                <h3>Transcription Leader Board</h3>
                <ul>
                <?php
					$result = mysql_query("select user_name, user_editcount from wiki_user order by user_editcount desc limit 10") or die(mysql_error());
					while ($row = mysql_fetch_array($result)) {
						printf("<li>%s - %s edits</li>", $row["user_name"], $row["user_editcount"]);
					}
					?>
                </ul>
                <h3>Active Users</h3>
                <ul><li><?php
					$result = mysql_query("SELECT COUNT( * ) FROM wiki_user WHERE user_editcount > 0") or die(mysql_error());
					while ($rows = mysql_fetch_array($result)) {
						$count = $rows[0];
						$total = (int)$count;
						$total_int = number_format($total);
						$total_str = strval($total_int);
					}
					mysql_close($con);
					mysql_free_result($result);
					echo $total_str;?></li></ul>
              </div>
              <div class="span3 twitter" data-twttr-id="twttr-sandbox-0">
                <a class="twitter-timeline"  href="https://twitter.com/LibraryofVA"  data-widget-id="414460525192294402">Tweets by @LibraryofVA</a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			  </div>
            </div>
        </div><!-- end footer -->
    </div><!-- end wrap -->
</body>
</html>
