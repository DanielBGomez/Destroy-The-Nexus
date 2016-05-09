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



####Game Over
If the game ends, it will allow users to store their score on the leaderboard or start a new game.

###Technologies used
The core of the application is write in PHP; to print information it's html, css as if a simple website concerned, and jQuery for dynamic interactions and prints.

We also used the following jQuery scripts:
 - **[jquery-cookie](https://github.com/carhartl/jquery-cookie)**
 - **imagesLoaded**
