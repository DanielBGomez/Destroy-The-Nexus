##Destroy The Nexus - Riot Games API Challenge 2016 Entry
######by Daniel BGomez (LAN: DanielGomez) & Ivan Aguilera (LAN: ScraguiGamer)

In this Web Based MiniGame, you must be fast and quick by pressing the correct spells to destroy the nexus that has a life equal to the champion mastery points of your best champions (Top 10). The Spells will approach to their key, and the closer they are when you press the key the more damage you deal to the nexus, but if you miss, you'll receive damage.

You think you can reach the maximum level using your best champion?

Since all data is based on the user's data, there is a different gaming experience for each user. 

###Live Url
> http://championmaster.danielbgomez.com/destroythenexus

### How does it Work?
To play you must enter a Summoner Name and select the region of the player to get the best Champion Mastery scores of the player.

Once the information is processed and validated, the game create cookies that store useful data and serve as control:
 - **summoner** - *Contains a JSON array that contains the name and id of the summoner.*
 - **champions** - *Contains a JSON array that contains the top 10 champions based on the score of the champion.*
 - **region** - *Contains the region selected in the Form (Only works to remember the region you selected if you want to play again).*
 - **level** - *Contains the level of the current game.*
 - **score** - *(Default = 0) Contains the previous level score of the current game.*


The game begins with the visual presentation, which is the playing area. The game itself download the images needed from the Riot servers. After all the images are loaded, the game board appears and the spells begin to appear randomly. If you miss the click or you press the key too soon, you lose health, but if you press the key in the right areas, you make damage to the nexus.

If the nexus ran out of health, you continue to the next level (Max of 10).

If you ran out of health, the game is over and it allows the users to store their score on the leaderboard or start a new game.

####How the Spells Appear?
After all the images are loaded and the levelAnnouncer fades Out, the games start an interval that *ThrowSpells*, with a time based on **'2000 - ( 300 * ( $level - 1 ) )'** miliseconds.

By default, the spells have an *Opacity* of **0.1** and an *marginLeft* of **475px**, this put the spells at the right of the rails in the game.

The **throwSpell** function generates an random number between **0** and **3** *( 4 possible numbers )* that are related to the champions spells like this:
```
K - Key

0 -  Q
1 -  W
2 -  E
3 -  R
```
Then, the game appends the related image at the rail container of the related spell and starts an animation that change the CSS properties of *Opacity* to **1** and *marginLeft* to **-105** with a speed calculated by **' 3000 - ( 300 * ( $level - 1 ) )'** miliseconds, if the animation ends, the user receive damage.

####How the Game Handle the Key Interactions?
When a key is pressed within the document, a function verify of the keyCode of the key pressed is one of the following:
```
Code - Key - K

113  -  Q  - 0
119  -  W  - 1
101  -  E  - 2
114  -  R  - 3
```
If so, it calls the function **keyPressed** that handle what to do.
 - Converts the **code** to the **k** value.
 - If there is no images in the rail related, the player **receiveDamage** and remove the first image in any rail.
 - Calculates the **marginLeft** of the first image found at the correspondient rail.
 - If the **marginLeft** is less than **-90px** or more or equal than **-15px**, the player **receiveDamage** because their are *'Miss'* areas.
   - Else:
    - If the **marginLeft** is less than **-15px** or more or equal than **-40px**, the player **makeDamage** with a base of *'50'* to the nexus, and adds **( 50 + ( $level * 10 ) )** to the Score.
    - If the **marginLeft** is less than **-40px** or more or equal than **-72px**, the player **makeDamage** with a base of *'100'* to the nexus, and adds **( 100 + ( $level * 10 ) )** to the Score.
    - If the **marginLeft** is less than **-72px** or more or equal than **-90px**, the player **makeDamage** with a base of *'300'* to the nexus, and adds **( 300 + ( $level * 10 ) )** to the Score.
  - Then the score update with the current score and the first image related to the key pressed is removed.

####How the Game Dealts Damage?
There are two functions to dealt damage:
 - **ReceiveDamage:**
   - That reduces the health of the player based on **'previousHealth - ( 25 - ( 2 * ( $level - 1 ) ) )'**.
     - If the health of the player is less or equal to 0, the game ends with a *Game Over*.
 - **MakeDamage:**
   - That reduces the health of the nexus based on **'previousHealth - ( $base * randomNumberBetween( 1 & 4 ) )'**.
     - If the health of the nexus is less or equal to 0, the game continues to the next level (Max of 10).

###Technologies used
The core of the application is write in PHP; to print information it's html, css as if a simple website concerned, and jQuery for dynamic interactions and prints.

We also used the following jQuery scripts:
 - **[jquery-cookie](https://github.com/carhartl/jquery-cookie)**
 - **imagesLoaded**
