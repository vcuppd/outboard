From: Georges Saucier <GSaucier@removed-for-privacy>
To: "feuerri@auburn.edu" <feuerri@auburn.edu>
Date: Wed, 13 May 2009 12:19:47 -0400
Subject: Outboard recommendations

Hi,
 
I use the outboard program you wrote and love it.  I find it does everything we need and in a very simple to use way.  I do not write code but on occasion I do try to "customize" stuff to better fit our needs.  I made a couple simple changes to the outboard and thought I would pass them along.
 
We found the color of the out dot and the color of the time back dot to be very close and hard to distinguish when it is at 5pm or out columns the simple solution for us was to make the out dot red.
 
Another issue we had was determining what time column the back dot was in.  If it was at the top or bottom of a section it was not a problem but after you get 3 or 4 rows away from the header it was easy to lose track of the column.  This was causing people to select the wrong back times.  The solution we used was to create dots with the appropriate numbers in them so it is very easy to tell if you picked the right column.  This also makes it very easy for the person checking when you'll be back.  My graphics aren't very advanced but they serve the purpose.  I have included the graphics in an attached zip file.  Just add them to the image directory, but i'm sure you already knew that.
 
Here is the mod I came up with.  In outboard.php I changed line 245 to this ...
       $back[$i] = "<img src=".$image_dir."/".$i.$dot_image." $alt>";
 
 
Since we use the outboard for our receptionists to keep track of who is in or out we found it very helpful to make the names in the first column an email link.  This allows the receptionists to send a quick email if the person on the phone doesn't want to leave a voicemail.  I made the outboard login for each user match the first part of their email address.  Example, jsmith@domain.com would have outboard login jsmith.
 
Here is the mod I came up with.  In outboard.php I changed line 274 to this ...
     echo "<td width=15% ".$user_bg."><a class=\"nobr\" name=\"".$row['userid']."\" href=\"mailto:".$row['userid']."@domain.com?subject=OutBoard message "."\">".$row['name']."</a></td>";
 
Since I am not a programmer I did not know how to make the domain a variable that could easily be defined in the config or what ever file does that.  I hard coded it into outboard.php file but a variable would be nice if you chose to use this suggestion.  Also a variable for the subject maybe.
 
Thanks, 
Georges Saucier 

